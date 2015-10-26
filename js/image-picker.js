var ImagePicker = function(element, options)
{
	var that = this;

	this.mainClass = 'image-picker-element';
	this.boxClass = 'image-picker-box';
	this.selectorClass = 'image-picker-selector';
	this.imageClass = 'image-picker-image';
	this.attrImageSrc = 'data-img-src';

	var defaults = {
		selectorWidth:  900,
		imageMaxHeight: 500,
		imageMaxWidth: 500
	}

	this.params = $.extend({}, defaults, options);

	this.element = element;
	this.element.hide();

	this.images = [];

	this.options = this.element.children('option')

	this.loadImages = function(callback)
	{
		var i = 0;

		this.totalImageWidth = 0;

		this.options.each(function(){
			that.images.push({
				id: $(this).val(),
				src: $(this).attr(that.attrImageSrc)
			});

			var tmpImg = new Image();
			tmpImg.src=that.images[that.images.length - 1].src;
			$(tmpImg).on('load', function(){
				that.totalImageWidth += that.getRealImageWidth(tmpImg.width, tmpImg.height);
				i++;
				if (i >= that.options.length) {
					that.setContent();
				}
			});
		});

		this.currentImage = (0 in this.images) ? this.images[0] : null;
	}

	this.setContent = function()
	{
		// If no image
		if (this.currentImage == null) {
			return;
		}

		this.$main = $('<div />', {
			'class': this.mainClass
		});

		// Create image box
		this.createBox();
		this.$main.append(this.$box);

		this.createSelector();
		this.$main.append(this.$selector);

		this.element.after(this.$main);

		this.updateCurrentImage(this.$selector);
		this.addListner();
	};

	this.createBox = function()
	{
		// Create box container
		this.$box = $('<div />', {
			'class': this.boxClass
		});

		// Create box image
		this.$boxImage = $('<img />', {
			'class': this.imageClass
		});
		this.$boxImage.css('max-height', this.params.imageMaxHeight);
		this.$boxImage.css('max-width', this.params.imageMaxWidth);
		this.$box.html(this.$boxImage);
	}

	this.createSelector = function()
	{
		this.$selector = $('<div />', {
			'class': this.selectorClass
		});
		this.$selector.css('max-height', this.params.imageMaxHeight);
		this.$selector.css('width', this.params.selectorWidth);

		this.$selectorInner = $('<div />');
		this.$selectorInner.css('max-height', this.params.imageMaxHeight);
		this.$selectorInner.css('width', this.totalImageWidth);

		this.$selector.html(this.$selectorInner);

		this.$selectorImages = [];

		for (var i = 0; i < this.images.length; i++) {
			this.$selectorImages[i] = $('<img />', {
				'src': this.images[i].src,
				'data-id': this.images[i].id
			});
			this.$selectorImages[i].css('max-height', this.params.selectorHeight);
			this.$selectorImages[i].css('max-width', this.params.imageMaxWidth);
			this.$selectorInner.append(this.$selectorImages[i]);
		};
	}

	this.updateCurrentImage = function()
	{
		this.$boxImage.attr('src', this.currentImage.src);
	}

	this.updateWidgetInput = function()
	{
		this.element.val(this.currentImage.id)
	}

	this.loadImages();

	this.addListner = function()
	{
		this.$box.on('click', function(){
			that.$selector.fadeIn();
		});

		this.$selector.find('img').on('click', function() {
			that.currentImage = {
				id: $(this).attr('data-id'),
				src: $(this).attr('src'),
			}
			that.updateCurrentImage();
			that.updateWidgetInput();
			that.$selector.fadeOut();
		});
	}

	this.getRealImageWidth = function(imageWidth, imageHeight)
	{
		var maxWidth = imageWidth > this.params.imageMaxWidth ? this.params.imageMaxWidth : imageWidth;

		var maxHeight = imageHeight > this.params.imageMaxHeight ? this.params.imageMaxHeight : imageHeight;

		var widthFromHeight = ( imageWidth * maxHeight ) / imageHeight;

		return maxWidth > widthFromHeight ? widthFromHeight : maxWidth;
	}
}

$.fn.imagePicker = function(params) {
    new ImagePicker(this, params);
};
