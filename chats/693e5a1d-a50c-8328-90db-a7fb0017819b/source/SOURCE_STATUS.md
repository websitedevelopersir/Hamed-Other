# Source Status

## Canonical file for next development

`latest-known.html`

## Confidence

- Layout/interaction logic: high confidence from the final responsive chat state.
- Exact production image URLs: unavailable in the recovered chat state.
- Hover CTA markup: not present in the final responsive code, but it had been explicitly requested earlier and is tracked as an unresolved regression.
- Standalone downloadable artifact: none found for this chat.

## Rule

Do not silently replace `latest-known.html` with an older fixed-size gallery. Any next version must start from this responsive baseline and record changes in `../logs/DEVELOPMENT_LOG.md`.
