import { ModelInterface } from './model.interface';
import { ProductInterface } from './product.interface';
import { UserInterface } from './user.interface';

export interface PurchaseInterface extends ModelInterface {
	product_id: number;
	user_id: number;
	from: string;
	amount: number;
	cost: number;
	paid: number;
	product?: ProductInterface;
	user?: UserInterface;
}
