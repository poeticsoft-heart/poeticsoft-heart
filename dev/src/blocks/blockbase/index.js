import metadata from './block.json';
import Edit from './edit.js';

const { registerBlockType } = wp.blocks;

registerBlockType(metadata.name, {
    ...metadata,
    edit: Edit,
    save: () => null,
});
