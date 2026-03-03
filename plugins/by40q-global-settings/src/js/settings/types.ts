/**
 * TypeScript definitions for the Global Settings React app.
 */

export type FieldType =
	| 'text'
	| 'textarea'
	| 'richtext'
	| 'toggle'
	| 'image'
	| 'url'
	| 'select';

export type FieldValue = string | boolean | number | null;

export interface SelectChoice {
	label: string;
	value: string;
}

export interface FieldDefinition {
	key: string;
	label: string;
	type: FieldType;
	tab: string;
	default: FieldValue;
	description: string;
	choices: SelectChoice[];
	value: FieldValue;
}

export interface TabDefinition {
	key: string;
	label: string;
	order: number;
	fields: FieldDefinition[];
}

export type SettingsSchema = TabDefinition[];

/** Flat map of field key → current value, used for the form state. */
export type FieldValues = Record<string, FieldValue>;

/** Shape of the REST GET response. */
export interface GetSettingsResponse {
	schema: SettingsSchema;
}

/** Shape of the REST POST request body. */
export interface SaveSettingsRequest {
	values: FieldValues;
}

/** Shape of the REST POST response. */
export interface SaveSettingsResponse {
	success: boolean;
	saved: FieldValues;
}
