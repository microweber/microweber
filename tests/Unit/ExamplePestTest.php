<?php

test('basic pest assertion', function () {
    expect(true)->toBeTrue();
});

test('basic arithmetic', function () {
    expect(2 + 2)->toBe(4);
});

test('string contains', function () {
    expect('microweber')->toContain('weber');
});
