function CU3ER(flashID) {
    this.version = "1.0b";
    this.flashID = flashID;
    this.currSlideNo = 1;
    this.onSlide = function() {};
    this.onTransition = function() {};

    this.registerFlash = function() {
      if (this.swf == null)
        this.swf = swfobject.getObjectById(this.flashID);
    }
    this.play = function() {
      this.registerFlash();
      this.swf.playCU3ER();
    }
    this.pause = function() {
      this.registerFlash();
      this.swf.pauseCU3ER();
    }
    this.next = function() {
      this.registerFlash();
      this.swf.next();
    }
    this.prev = function() {
      this.registerFlash();
      this.swf.prev();
    }
    this.skipTo = function(slideNo) {
      this.registerFlash();
      this.swf.skipTo(slideNo-1);
    }
    this.onSlideChangeStart = function(slideNo) {
      this.currSlide = slideNo;
      this.onTransition(slideNo);
    }
    this.onSlideChange = function(slideNo) {
      this.currSlide = slideNo;
      this.onSlide(slideNo);
    }
}