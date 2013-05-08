<?php

/// @cond ALL

/**
 * Identity formatter. Returns what it's given. Implemented for consistency.
 */
class LuminousIdentityFormatter extends LuminousFormatter {
  public function format($str) {
    return $str;
  }
}

/// @endcond