import { ModelInterface } from './model.interface';
import { OrderInterface } from './order.interface';
import { ProductInterface } from './product.interface';

export interface OrderItemInterface extends ModelInterface {
	quantity: number;
	product?: ProductInterface;
	order?: OrderInterface;
}
