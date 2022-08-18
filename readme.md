Current supported TCA types for Placeholder backend Feature:

- input

inn progress:

- RTE Plugin for Placeholder (show Placeholder Content, add placeholder)
- RTE Highlight Placeholder inside RTE Text

look into:

- clearable inputs (.t3js-clearable)

Placeholder in `pages`:

in this case add the placeholder processor to your page dataProcessors

Example:
    page.10.dataProcessing {
        141988 = SebastianStein\Placeholder\DataProcessing\PlaceholderProcessor
    }
