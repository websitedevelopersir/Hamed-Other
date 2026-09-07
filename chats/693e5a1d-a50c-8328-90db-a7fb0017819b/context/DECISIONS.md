# Decisions

## Final known decisions

1. Gallery implementation stays as plain Custom HTML/CSS/JavaScript suitable for Elementor HTML widget/custom code.
2. Desktop layout shows 5 cyclic images.
3. Mobile layout shows 3 cyclic images.
4. Clicking any visible image makes that image the active center item.
5. Auto advance interval is `3000ms`.
6. Hover pauses autoplay; leaving the gallery resumes it.
7. Responsive sizing must preserve the visual hierarchy rather than assigning one fixed size to every item.
8. Width proportions:
   - Desktop: `[0.55, 0.75, 1, 0.75, 0.55]`
   - Mobile: `[0.75, 1, 0.75]`
9. Original aspect ratios remain the design reference:
   - Center: `520 / 374`
   - Adjacent: `348 / 250`
   - Outer: `244 / 300`
10. Images use `20px` rounded corners.
11. Font preference is `YekanbakhR`.

## Do not assume

- Do not assume the missing image URLs.
- Do not assume the center hover CTA was intentionally removed; it disappeared from the latest responsive code and is tracked as a regression.
- Do not use an older non-responsive implementation as the new baseline.
