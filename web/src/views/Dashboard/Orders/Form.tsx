import { useArrayComplex, useMode, useNullable, useToggle } from '@avidian/hooks';
import React, { FC, useEffect } from 'react';
import { useHistory, useRouteMatch } from 'react-router';
import { useForm } from 'react-hook-form';
import Button from '../../../components/Buttons/Button';
import View from '../../../components/Dashboard/View';
import ViewBody from '../../../components/Dashboard/View/Body';
import ViewHead from '../../../components/Dashboard/View/Head';
import FormBody from '../../../components/Forms/Body';
import Group from '../../../components/Forms/Group';
import Input from '../../../components/Forms/Input';
import Label from '../../../components/Forms/Label';
import { OrderInterface, OrderStatuses } from '../../../interfaces/order.interface';
import { formatNumber, handleError } from '../../../helpers';
import { createOrder, getOrder, updateOrder } from '../../../queries/order.queries';
import Spinner from '../../../components/Spinner';
import { useQuery } from 'react-query';
import Select from '../../../components/Forms/Select';
import { FormData } from '../../../libraries/FormData.library';
import { getProducts } from '../../../queries/product.queries';
import { getUsers } from '../../../queries/user.queries';
import { UserRoles } from '../../../interfaces/user.interface';
import { ProductInterface } from '../../../interfaces/product.interface';
import SelectedProduct from '../../../components/Orders/SelectedProduct';
import Item from '../../../components/Orders/Numpad/Item';

type Props = {};

type Inputs = Pick<OrderInterface, 'biller_id' | 'customer_id' | 'paid' | 'status'>;

type SelectedProduct = {
	product: ProductInterface;
	quantity: number;
	active: boolean;
};

const Form: FC<Props> = (props) => {
	const [processing, setProcessing] = useToggle(false);
	const [mode, setMode] = useMode();
	const history = useHistory();
	const match = useRouteMatch<{ id: string }>();
	const { register, handleSubmit, reset, setValue } = useForm<Inputs>();
	const { data: products } = useQuery('products', getProducts);
	const { data: employees } = useQuery('employees', () => getUsers({ role: UserRoles.EMPLOYEE }));
	const { data: admins } = useQuery('admins', () => getUsers({ role: UserRoles.ADMIN }));
	const { data: users } = useQuery('users', () => getUsers());
	const { array: selectedProducts, push, remove, update, clear, filter } = useArrayComplex<SelectedProduct>();

	const staffs = [...(employees || []), ...(admins || [])];

	const fetchOrder = async () => {
		setProcessing(true);
		try {
			const order = await getOrder(match.params.id);

			if (order.biller) {
				setValue('biller_id', order.biller.id!);
			}

			if (order.customer) {
				setValue('customer_id', order.customer.id!);
			}

			setValue('paid', order.paid);
			setValue('status', order.status);

			setMode('Edit');
		} catch (error) {
			handleError(error);
			history.goBack();
		} finally {
			setProcessing(false);
		}
	};

	const submit = async (data: Inputs) => {
		setProcessing(true);
		try {
			const payload = new FormData(data);

			await (mode === 'Add' ? createOrder(payload) : updateOrder(match.params.id, payload));

			if (mode === 'Add') {
				reset();
			}

			toastr.success(`${mode} Order successful.`);
		} catch (error) {
			handleError(error);
		} finally {
			setProcessing(false);
		}
	};

	useEffect(() => {
		if (match.path.includes('edit')) {
			fetchOrder();
		}
		// eslint-disable-next-line
	}, []);

	return (
		<View>
			<ViewHead>
				<h3>{mode} Order</h3>
				<Button
					buttonSize='sm'
					color='indigo'
					className='ml-auto w-20 flex items-center justify-center'
					onClick={(e) => {
						e.preventDefault();
						history.goBack();
					}}>
					Back
				</Button>
			</ViewHead>
			<ViewBody>
				<form onSubmit={handleSubmit(submit)}>
					<FormBody>
						<Group className='md:w-1/2'>
							<Label htmlFor='biller_id'>Biller</Label>
							<Select {...register('biller_id')} inputSize='sm' disabled={processing}>
								<option selected disabled>
									{' '}
									-- Select --{' '}
								</option>
								{staffs?.map((staff, index) => (
									<option value={staff.id} key={index}>
										{staff.name}
									</option>
								))}
							</Select>
						</Group>
						<Group className='md:w-1/2'>
							<Label htmlFor='customer_id'>Customer</Label>
							<Select {...register('customer_id')} inputSize='sm' disabled={processing}>
								<option selected disabled>
									{' '}
									-- Select --{' '}
								</option>
								{users?.map((user, index) => (
									<option value={user.id} key={index}>
										{user.name}
									</option>
								))}
							</Select>
						</Group>
						<Group className='md:w-1/2'>
							<Label htmlFor='paid'>Paid</Label>
							<Input {...register('paid')} type='number' placeholder='Paid' inputSize='sm' disabled={processing} />
						</Group>
						<Group>
							<hr className='my-6' />
						</Group>
						<Group className='md:w-1/2 xl:w-1/4'>
							{selectedProducts.map((item, index) => (
								<SelectedProduct
									key={index}
									name={item.product.name}
									active={item.active}
									price={item.product.price}
									quantity={item.quantity}
									onSelect={(active) => {
										update(index, {
											...item,
											active: !active,
										});

										if (!active) {
											const activeProduct = selectedProducts.find(
												(i) => i.active && item.product.id !== i.product.id
											);
											if (activeProduct) {
												const index = selectedProducts.findIndex(
													({ product }) => product.id === activeProduct.product.id
												);

												update(index, { ...activeProduct, active: false });
											}
										}
									}}
								/>
							))}
							<div className='text-right w-full pr-4 mt-8'>
								<h4 className='text-gray-600 border-t-4 border-gray-600 inline ml-auto px-1 pt-1'>
									Total: ₱
									{formatNumber(selectedProducts.reduce((prev, next) => prev + next.product.price * next.quantity, 0))}
								</h4>
							</div>
						</Group>
						<Group className='md:w-1/2 xl:w-3/4'>
							<FormBody>
								{products
									?.filter((product) => !selectedProducts.find((item) => item.product.id === product.id))
									.map((product, index) => (
										<Group className='lg:w-1/2 xl:w-1/6 mt-2 flex items-center justify-center' key={index}>
											<div
												className='h-40 bg-cover bg-no-repeat w-40 cursor-pointer relative rounded border border-gray-200 shadow'
												onClick={(e) => {
													e.preventDefault();
													push({ product, active: false, quantity: 1 });
												}}
												style={{
													backgroundImage: product.picture ? `url(${product.picture.url})` : 'rgb(50, 50, 50)',
												}}>
												<span className='bg-white group hover:bg-gray-400 text-xs p-1 absolute top-1 left-1 rounded border-gray-200 border'>
													<i className='fas fa-info-circle group-hover:text-white'></i>
												</span>
												<span className='bg-white text-xs p-1 absolute top-1 right-1 rounded border-gray-200 border'>
													₱{product.price}
												</span>
												<span className='bg-white text-xs p-1 absolute bottom-1 left-1 rounded border-gray-200 border'>
													{product.name}
												</span>
											</div>
										</Group>
									))}
							</FormBody>
						</Group>
						<Group className='py-4 pl-10'>
							<h5 className='mb-4'>Controls</h5>
							<div className='flex'>
								<Button
									className='mx-1'
									color='indigo'
									buttonSize='sm'
									disabled={!selectedProducts.find((item) => item.active)}>
									Info
								</Button>
								<Button
									className='mx-1'
									color='red'
									buttonSize='sm'
									onClick={(e) => {
										e.preventDefault();
										const activeProduct = selectedProducts.find((item) => item.active);
										if (activeProduct) {
											const index = selectedProducts.findIndex(
												(item) => item.product.id === activeProduct.product.id
											);
											remove(index);
										}
									}}
									disabled={!selectedProducts.find((item) => item.active)}>
									Remove
								</Button>
							</div>
							<div className='w-full my-10'>
								<div className='flex'>
									<Item>1</Item>
									<Item>2</Item>
									<Item>3</Item>
								</div>
								<div className='flex'>
									<Item>4</Item>
									<Item>5</Item>
									<Item>6</Item>
								</div>
								<div className='flex'>
									<Item>7</Item>
									<Item>8</Item>
									<Item>9</Item>
								</div>
								<div className='flex'>
									<Item>
										<i className='fas fa-trash'></i>
									</Item>
									<Item>0</Item>
									<Item>
										<i className='fas fa-backspace'></i>
									</Item>
								</div>
							</div>
						</Group>
						<Group>
							<hr className='my-6' />
						</Group>
						<Group>
							<Button type='submit' buttonSize='sm' className='mt-0 sm:mt-4' disabled={processing}>
								{processing ? <Spinner className='mr-2' /> : null}
								Save
							</Button>
						</Group>
					</FormBody>
				</form>
			</ViewBody>
		</View>
	);
};

export default Form;
