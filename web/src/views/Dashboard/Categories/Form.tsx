import { useMode, useNullable, useToggle } from '@avidian/hooks';
import React, { FC, useEffect, useRef } from 'react';
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
import { CategoryInterface } from '../../../interfaces/category.interface';
import { handleError } from '../../../helpers';
import { createCategory, getCategory, updateCategory } from '../../../queries/category.queries';
import Spinner from '../../../components/Spinner';
import HiddenFile from '../../../components/Forms/HiddenFile';

type Props = {};

type Inputs = Pick<CategoryInterface, 'code' | 'name'>;

const Form: FC<Props> = (props) => {
	const [processing, setProcessing] = useToggle(false);
	const [preview, setPreview] = useNullable<string>();
	const [mode, setMode] = useMode();
	const history = useHistory();
	const match = useRouteMatch<{ id: string }>();
	const fileRef = useRef<HTMLInputElement>(null);
	const { register, handleSubmit, reset, setValue } = useForm<Inputs>();
	const [picture, setPicture] = useNullable<File>();

	const fileReader = new FileReader();

	fileReader.addEventListener('loadend', (e) => {
		if (e.target?.result) {
			setPreview(e.target.result.toString());
		}
	});

	const fetchCategory = async () => {
		setProcessing(true);
		try {
			const category = await getCategory(match.params.id);

			if (category.picture) {
				setPreview(category.picture.url);
			}

			setValue('code', category.code);
			setValue('name', category.name);
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
			const payload = new FormData();

			payload.set('name', data.name);
			payload.set('code', data.code);

			if (picture) {
				payload.set('picture', picture);
			}

			await (mode === 'Add' ? createCategory(payload) : updateCategory(match.params.id, payload));

			if (mode === 'Add') {
				reset();
				setPicture(null);
			}

			toastr.success(`${mode} Category successful.`);
		} catch (error) {
			handleError(error);
		} finally {
			setProcessing(false);
		}
	};

	useEffect(() => {
		if (match.path.includes('edit')) {
			fetchCategory();
		}
		// eslint-disable-next-line
	}, []);

	return (
		<View>
			<ViewHead>
				<h3>{mode} Category</h3>
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
						<Group className='text-center'>
							<Label htmlFor='picture'>Picture</Label>
							<img
								src={preview || 'https://via.placeholder.com/200'}
								alt='Category Thumbnail'
								className={`rounded-full h-52 w-52 shadow-lg mx-auto ${!processing ? 'cursor-pointer' : ''}`}
								onClick={(e) => {
									e.preventDefault();
									if (!processing) {
										fileRef.current?.click();
									}
								}}
							/>
							<HiddenFile
								ref={fileRef}
								name='picture'
								id='picture'
								onUpload={(files) => {
									if (files.length > 0 && !processing) {
										const file = files[0];
										fileReader.readAsDataURL(file);
										setPicture(file);
									}
								}}
								disabled={processing}
							/>
						</Group>
						<Group className='md:w-1/2'>
							<Label htmlFor='code'>Code</Label>
							<Input {...register('code')} type='text' placeholder='Code' inputSize='sm' disabled={processing} />
						</Group>
						<Group className='md:w-1/2'>
							<Label htmlFor='name'>Name</Label>
							<Input {...register('name')} type='text' placeholder='Name' inputSize='sm' disabled={processing} />
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
