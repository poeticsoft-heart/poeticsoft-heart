import metadata from './block.json';
import Edit from './edit.js';

const { registerBlockType } = wp.blocks;
const { domReady } = wp;

domReady(() => {
    registerBlockType(metadata.name, {
        ...metadata,
        edit: Edit,
        save: () => null,
    });
});
