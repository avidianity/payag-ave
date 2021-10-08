import { FileInterface } from './file.interface';
import { ModelInterface } from './model.interface';
import { ProductInterface } from './product.interface';

export interface CategoryInterface extends ModelInterface {
	code: string;
	name: string;
	picture?: FileInterface;
	products?: ProductInterface[];
}
