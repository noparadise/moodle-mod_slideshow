/**
 * This module allows the user to position media on a slide by dragging an outline
 * box.
 */
M.local_slideshow = {
    Y : null,
    transaction : [],
    init : function(Y){
			this.Y = Y;
			this.slide = this.Y.one("#slide");
			this.width = this.Y.one("#id_mediawidth");
			this.height = this.Y.one("#id_mediaheight");

			this.mediaOutline = this.Y.one("#media_outline");
			this.mediaOutline.plug(this.Y.Plugin.Drag);
			
			// Set the outline box to the correct size and position.
			this.updateMediaOutlineSize();
			
			var x = parseInt(this.Y.one("input[name=mediaX]").get("value"), 10);
			var y = parseInt(this.Y.one("input[name=mediaY]").get("value"), 10);
			if(!isNaN(x) && !isNaN(y)) {
				// Absolute coordinates, have to add slide's start coords.
				this.mediaOutline.setXY([this.slide.get("region").left + x, this.slide.get("region").top + y]);
			}
			else {
				this.Y.one("input[name=mediaX]").set("value", "0");
				this.Y.one("input[name=mediaY]").set("value", "0");
				this.mediaOutline.setXY([this.slide.get("region").left, this.slide.get("region").top]);
			}

			this.slide.on("click", this.updateMediaCoords);
			this.Y.one("#id_mediawidth").on("blur", this.updateMediaOutlineSize);
			this.Y.one("#id_mediaheight").on("blur", this.updateMediaOutlineSize);
    },

		// Sets the media X and Y values taken from the draggable box's topleft X, Y coords.
		updateMediaCoords : function(e) {
			var x = M.local_slideshow.mediaOutline.getX();
			var y = M.local_slideshow.mediaOutline.getY();
			// Relative to the slide instead of absolute page coords.
			var relativeX = x - M.local_slideshow.slide.get("region").left;
			var relativeY = y - M.local_slideshow.slide.get("region").top;
			M.local_slideshow.Y.one("input[name=mediaX]").set("value", relativeX);
			M.local_slideshow.Y.one("input[name=mediaY]").set("value", relativeY);
		},

		updateMediaOutlineSize : function() {
			var mediaOutline = M.local_slideshow.mediaOutline;
			var width = parseInt(M.local_slideshow.Y.one("#id_mediawidth").get("value"), 10);
			var height = parseInt(M.local_slideshow.Y.one("#id_mediaheight").get("value"), 10);
			if(!isNaN(width) && !isNaN(height)) {
				mediaOutline.set("offsetWidth", width);
				mediaOutline.set("offsetHeight", height);
			}
		},
  }
