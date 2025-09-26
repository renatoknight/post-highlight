import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import Save from './save';
import './style.css';
import './editor.css';

registerBlockType('custom/post-highlight', {
  edit: Edit,
  save: Save,
});