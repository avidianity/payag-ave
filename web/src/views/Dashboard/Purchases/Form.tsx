import { useMode, useToggle } from '@avidian/hooks';
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
import { PurchaseInterface } from '../../../interfaces/purchase.interface';
import { handleError } from '../../../helpers';
import { createPurchase, getPurchase, updatePurchase } from '../../../queries/purchase.queries';
import Spinner from '../../../components/Spinner';
import { FormData } from '../../../libraries/FormData.library';
import { useQuery } from 'react-query';
import { getProducts } from '../../../queries/product.queries';
import Select from '../../../components/Forms/Select';

type Props = {};

type Inputs = Pick<PurchaseInterface, 'product_id' | 'from' | 'amount' | 'cost' | 'paid'>;

const Form: FC<Props> = (props) => {
	const [processing, setProcessing] = useToggle(false);
	const [mode, setMode] = useMode();
	const history = useHistory();
	const match = useRouteMatch<{ id: string }>();
	const { register, handleSubmit, reset, setValue } = useForm<Inputs>();
	const { data: products } = useQuery('products', getProducts);

	const fetchPurchase = async () => {
		setProcessing(true);
		try {
			const purchase = await getPurchase(match.params.id);

			if (purchase.product) {
				setValue('product_id', purchase.product.id!);
			}

			setValue('from', purchase.from);
			setValue('amount', purchase.amount);
			setValue('cost', purchase.cost);
			setValue('paid', purchase.paid);

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

			await (mode === 'Add' ? createPurchase(payload) : updatePurchase(match.params.id, payload));

			if (mode === 'Add') {
				reset();
			}

			toastr.success(`${mode} Purchase successful.`);
		} catch (error) {
			handleError(error);
		} finally {
			setProcessing(false);
		}
	};

	useEffect(() => {
		if (match.path.includes('edit')) {
			fetchPurchase();
		}
		// eslint-disable-next-line
	}, []);

	return (
		<View>
			<ViewHead>
				<h3>{mode} Purchase</h3>
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
						<Group className='md:w-1/3'>
							<Label htmlFor='product_id'>Product</Label>
							<Select {...register('product_id')} inputSize='sm' disabled={processing}>
								<option value='' selected disabled>
									-- Select --
								</option>
								{products?.map((product, index) => (
									<option value={product.id} key={index}>
										{product.name}
									</option>
								))}
							</Select>
						</Group>
						<Group className='md:w-1/3'>
							<Label htmlFor='from'>Supplier Name</Label>
							<Input {...register('from')} type='text' placeholder='Supplier Name' inputSize='sm' disabled={processing} />
						</Group>
						<Group className='md:w-1/3'>
							<Label htmlFor='amount'>Amount</Label>
							<Input {...register('amount')} type='number' placeholder='Amount' inputSize='sm' disabled={processing} />
						</Group>
						<Group className='md:w-1/2'>
							<Label htmlFor='cost'>Cost</Label>
							<Input {...register('cost')} type='number' placeholder='Cost' inputSize='sm' disabled={processing} />
						</Group>
						<Group className='md:w-1/2'>
							<Label htmlFor='paid'>Paid</Label>
							<Input {...register('paid')} type='number' placeholder='Paid' inputSize='sm' disabled={processing} />
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
