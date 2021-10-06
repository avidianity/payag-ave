import { ModelInterface } from './model.interface';

export interface FileInterface extends ModelInterface {
	type: string;
	name: string;
	size: string;
	url: string;
}
