<?php

// the identity scanner. Does what you expect.
// Implemented for consistency.

class LuminousIdentityScanner extends LuminousScanner {
  public function main() {
    $this->record($this->string(), null);
  }
}