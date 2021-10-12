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
import { UserInterface, UserRoles } from '../../../interfaces/user.interface';
import { handleError } from '../../../helpers';
import { createUser, getUser, updateUser } from '../../../queries/user.queries';
import Spinner from '../../../components/Spinner';
import HiddenFile from '../../../components/Forms/HiddenFile';
import { FormData } from '../../../libraries/FormData.library';
import { upperFirst } from 'lodash';
import Select from '../../../components/Forms/Select';
import Checkbox from '../../../components/Forms/Checkbox';

type Props = {
	roles: UserRoles[];
};

type Inputs = Pick<UserInterface, 'name' | 'email' | 'password' | 'phone' | 'role' | 'status'>;

const Form: FC<Props> = ({ roles }) => {
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

	const fetchUser = async () => {
		setProcessing(true);
		try {
			const user = await getUser(match.params.id);

			if (user.picture) {
				setPreview(user.picture.url);
			}

			setValue('name', user.name);
			setValue('email', user.email);
			setValue('phone', user.phone);
			setValue('role', user.role);
			setValue('status', user.status);
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
			const payload = new FormData(data, { booleansAsIntegers: true });

			if (picture) {
				payload.set('picture', picture);
			}

			await (mode === 'Add' ? createUser(payload) : updateUser(match.params.id, payload));

			if (mode === 'Add') {
				reset();
				setPicture(null);
			}

			toastr.success(`${mode} ${upperFirst(data.role)} successful.`);
		} catch (error) {
			handleError(error);
		} finally {
			setProcessing(false);
		}
	};

	useEffect(() => {
		if (match.path.includes('edit')) {
			fetchUser();
		}
		// eslint-disable-next-line
	}, []);

	return (
		<View>
			<ViewHead>
				<h3>
					{mode} {roles.map(upperFirst).join(' - ')}
				</h3>
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
								alt='User Thumbnail'
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
						<Group className='md:w-1/3'>
							<Label htmlFor='name'>Name</Label>
							<Input {...register('name')} type='text' placeholder='Name' inputSize='sm' disabled={processing} />
						</Group>
						<Group className='md:w-1/3'>
							<Label htmlFor='email'>Email</Label>
							<Input {...register('email')} type='text' placeholder='Email' inputSize='sm' disabled={processing} />
						</Group>
						<Group className='md:w-1/3'>
							<Label htmlFor='phone'>Phone</Label>
							<Input {...register('phone')} type='text' placeholder='Phone' inputSize='sm' disabled={processing} />
						</Group>
						<Group className='md:w-1/2'>
							<Label htmlFor='password'>Password</Label>
							<Input {...register('password')} type='password' placeholder='Password' inputSize='sm' disabled={processing} />
						</Group>
						<Group className='md:w-1/2'>
							<Label htmlFor='role'>Role</Label>
							<Select {...register('role')} inputSize='sm' disabled={processing}>
								<option value='' selected disabled>
									-- Select --
								</option>
								{roles.map((role, index) => (
									<option value={role} key={index}>
										{upperFirst(role)}
									</option>
								))}
							</Select>
						</Group>
						<Group>
							<Label htmlFor='status'>Active</Label>
							<Checkbox {...register('status')} id='status' label='Active' disabled={processing} />
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
