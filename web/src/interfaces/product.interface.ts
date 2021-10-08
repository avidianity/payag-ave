import { CategoryInterface } from './category.interface';
import { FileInterface } from './file.interface';
import { ModelInterface } from './model.interface';

export interface ProductInterface extends ModelInterface {
	code: string;
	name: string;
	price: number;
	cost: number;
	quantity: number;
	category_id: number;
	picture?: FileInterface;
	category?: CategoryInterface;
}
