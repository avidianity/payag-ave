import { CategoryInterface } from './category.interface';
import { FileInterface } from './file.interface';
import { ModelInterface } from './model.interface';
import { OrderItemInterface } from './order-item.interface';

export interface ProductInterface extends ModelInterface {
	code: string;
	name: string;
	description?: string;
	price: number;
	cost: number;
	quantity: number;
	category_id: number;
	picture?: FileInterface;
	category?: CategoryInterface;
	items?: OrderItemInterface[];
}
