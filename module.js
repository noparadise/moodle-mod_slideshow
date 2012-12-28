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
			var slideRegion = this.slide.get("region");
			this.slideTop = slideRegion.top;
			this.slideRight = slideRegion.right;
			this.slideBottom = slideRegion.bottom;
			this.slideLeft = slideRegion.left;

			this.mediaOutline = this.Y.one("#media_outline");
			this.mediaOutline.plug(this.Y.Plugin.Drag);
			
			// Set the outline box to the correct position.
			var x = parseInt(this.Y.one("#id_mediaX").get("value"), 10);
			var y = parseInt(this.Y.one("#id_mediaY").get("value"), 10);
			if(x) {
				// Absolute coordinates, have to add slide's start coords.
				this.mediaOutline.setXY([this.slideLeft + x, this.slideTop + y]);
			}

			this.slide.on("click", this.getMediaCoords);
    },
		getMediaCoords : function(e) {
			var x = M.local_slideshow.mediaOutline.getX();
			var y = M.local_slideshow.mediaOutline.getY();
			var relativeMX = x - M.local_slideshow.slide.get("region").left;
			var relativeMY = y - M.local_slideshow.slide.get("region").top;
			M.local_slideshow.Y.one("#id_mediaX").set("value", relativeMX);
			M.local_slideshow.Y.one("#id_mediaY").set("value", relativeMY);
		},
  }
