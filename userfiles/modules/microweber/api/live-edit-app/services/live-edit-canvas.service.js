export const LiveEditCanvasService = {
  getFrame: () => {
      return document.getElementById('live-editor-frame');
  },
  getWindow: () => {
      const frame  = LiveEditCanvasService.getFrame();
      if(frame) {
          return frame.contentWindow;
      }
  },
    getDocument: () => {
        const frame  = LiveEditCanvasService.getFrame();
        if(frame) {
            return frame.contentWindow.document;
        }
    }
};
