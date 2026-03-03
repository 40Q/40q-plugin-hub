/**
 * FieldRenderer — routes to the correct field component based on `field.type`.
 */

import { createElement } from '@wordpress/element';
import type { FieldDefinition, FieldValue } from '../../types';
import TextField      from './TextField';
import TextareaField  from './TextareaField';
import RichtextField  from './RichtextField';
import ToggleField    from './ToggleField';
import ImageField     from './ImageField';
import UrlField       from './UrlField';
import SelectField    from './SelectField';

interface FieldRendererProps {
	field:    FieldDefinition;
	value:    FieldValue;
	onChange: ( value: FieldValue ) => void;
}

export default function FieldRenderer( { field, value, onChange }: FieldRendererProps ) {
	const wrapperClass = `by40q-field by40q-field--${ field.type }`;

	const fieldEl = ( () => {
		switch ( field.type ) {
			case 'text':
				return createElement( TextField, { field, value, onChange } );
			case 'textarea':
				return createElement( TextareaField, { field, value, onChange } );
			case 'richtext':
				return createElement( RichtextField, { field, value, onChange } );
			case 'toggle':
				return createElement( ToggleField, { field, value, onChange } );
			case 'image':
				return createElement( ImageField, { field, value, onChange } );
			case 'url':
				return createElement( UrlField, { field, value, onChange } );
			case 'select':
				return createElement( SelectField, { field, value, onChange } );
			default:
				return createElement( TextField, { field, value, onChange } );
		}
	} )();

	return createElement(
		'div',
		{ className: wrapperClass },
		fieldEl,
		field.description && createElement(
			'p',
			{ className: 'by40q-field__description description' },
			field.description
		)
	);
}
