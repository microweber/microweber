/**
 * --------------------------------------------------------------------------
 * Bootstrap (v5.0.2): popover.js
 * Licensed under MIT (https://github.com/twbs/bootstrap/blob/main/LICENSE)
 * --------------------------------------------------------------------------
 */

import { defineJQueryPlugin } from './util/index'
import SelectorEngine from './dom/selector-engine'
import Tooltip from './tooltip'

/**
 * ------------------------------------------------------------------------
 * Constants
 * ------------------------------------------------------------------------
 */

const NAME = 'popover'
const DATA_KEY = 'bs.popover'
const EVENT_KEY = `.${DATA_KEY}`
const CLASS_PREFIX = 'bs-popover'
const BSCLS_PREFIX_REGEX = new RegExp(`(^|\\s)${CLASS_PREFIX}\\S+`, 'g')

const Default = {
  ...Tooltip.Default,
  placement: 'right',
  offset: [0, 8],
  trigger: 'click',
  content: '',
  template: '<div class="popover" role="tooltip">' +
              '<div class="popover-arrow"></div>' +
              '<h3 class="popover-header"></h3>' +
              '<div class="popover-body"></div>' +
            '</div>'
}

const DefaultType = {
  ...Tooltip.DefaultType,
  content: '(string|element|function)'
}

const Event = {
  HIDE: `hide${EVENT_KEY}`,
  HIDDEN: `hidden${EVENT_KEY}`,
  SHOW: `show${EVENT_KEY}`,
  SHOWN: `shown${EVENT_KEY}`,
  INSERTED: `inserted${EVENT_KEY}`,
  CLICK: `click${EVENT_KEY}`,
  FOCUSIN: `focusin${EVENT_KEY}`,
  FOCUSOUT: `focusout${EVENT_KEY}`,
  MOUSEENTER: `mouseenter${EVENT_KEY}`,
  MOUSELEAVE: `mouseleave${EVENT_KEY}`
}

const CLASS_NAME_FADE = 'fade'
const CLASS_NAME_SHOW = 'show'

const SELECTOR_TITLE = '.popover-header'
const SELECTOR_CONTENT = '.popover-body'

/**
 * ------------------------------------------------------------------------
 * Class Definition
 * ------------------------------------------------------------------------
 */

class Popover extends Tooltip {
  // Getters

  static get Default() {
    return Default
  }

  static get NAME() {
    return NAME
  }

  static get Event() {
    return Event
  }

  static get DefaultType() {
    return DefaultType
  }

  // Overrides

  isWithContent() {
    return this.getTitle() || this._getContent()
  }

  getTipElement() {
    if (this.tip) {
      return this.tip
    }

    this.tip = super.getTipElement()

    if (!this.getTitle()) {
      SelectorEngine.findOne(SELECTOR_TITLE, this.tip).remove()
    }

    if (!this._getContent()) {
      SelectorEngine.findOne(SELECTOR_CONTENT, this.tip).remove()
    }

    return this.tip
  }

  setContent() {
    const tip = this.getTipElement()

    // we use append for html objects to maintain js events
    this.setElementContent(SelectorEngine.findOne(SELECTOR_TITLE, tip), this.getTitle())
    let content = this._getContent()
    if (typeof content === 'function') {
      content = content.call(this._element)
    }

    this.setElementContent(SelectorEngine.findOne(SELECTOR_CONTENT, tip), content)

    tip.classList.remove(CLASS_NAME_FADE, CLASS_NAME_SHOW)
  }

  // Private

  _addAttachmentClass(attachment) {
    this.getTipElement().classList.add(`${CLASS_PREFIX}-${this.updateAttachment(attachment)}`)
  }

  _getContent() {
    return this._element.getAttribute('data-bs-content') || this._config.content
  }

  _cleanTipClass() {
    const tip = this.getTipElement()
    const tabClass = tip.getAttribute('class').match(BSCLS_PREFIX_REGEX)
    if (tabClass !== null && tabClass.length > 0) {
      tabClass.map(token => token.trim())
        .forEach(tClass => tip.classList.remove(tClass))
    }
  }

  // Static

  static jQueryInterface(config) {
    return this.each(function () {
      const data = Popover.getOrCreateInstance(this, config)

      if (typeof config === 'string') {
        if (typeof data[config] === 'undefined') {
          throw new TypeError(`No method named "${config}"`)
        }

        data[config]()
      }
    })
  }
}

/**
 * ------------------------------------------------------------------------
 * jQuery
 * ------------------------------------------------------------------------
 * add .Popover to jQuery only if jQuery is present
 */

defineJQueryPlugin(Popover)

export default Popover
