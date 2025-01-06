
export default {
    install: (app, options) => {
      app.config.globalProperties.$lang = (key) => {
        return mw.lang(key);
      }
    }
  }
