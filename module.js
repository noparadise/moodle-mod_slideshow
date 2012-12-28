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
			console.log("Regions top-left coords are: " + this.slideTop + ", " + this.slideLeft);

			this.slide.on("click", this.getMediaCoords);
    },
		getMediaCoords : function(e) {
			var mX = e.pageX;
			var mY = e.pageY;
			var relativeMX = mX - M.local_slideshow.slide.get("region").left;
			var relativeMY = mY - M.local_slideshow.slide.get("region").top;
			console.log("Clicked relative coords: " + relativeMY + ", " + relativeMX);
			M.local_slideshow.Y.one("#id_mediaX").set("value", relativeMX);
			M.local_slideshow.Y.one("#id_mediaY").set("value", relativeMY);
		},
  }
