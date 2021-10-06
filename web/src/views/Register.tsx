import React, { FC, useContext, useEffect } from 'react';
import Button from '../components/Buttons/Button';
import Card from '../components/Card';
import Body from '../components/Card/Body';
import Header from '../components/Card/Header';
import CenterFlexContainer from '../components/CenterFlexContainer';
import Input from '../components/Forms/Input';
import Link from '../components/Link';
import { routes } from '../routes';
import logo from '../assets/logo-rounded.png';
import { UserInterface } from '../interfaces/user.interface';
import { useToggle } from '@avidian/hooks';
import { useForm } from 'react-hook-form';
import Spinner from '../components/Spinner';
import { getUserMainPage, handleError, route } from '../helpers';
import axios from 'axios';
import { AuthContext } from '../contexts/auth.context';
import { useHistory } from 'react-router';

type Props = {};

type Inputs = Pick<UserInterface, 'name' | 'phone' | 'email' | 'password'> & {
	password_confirmation: boolean;
};

const Register: FC<Props> = (props) => {
	const [processing, setProcessing] = useToggle(false);
	const { register, handleSubmit, reset } = useForm<Inputs>();
	const { user } = useContext(AuthContext);
	const history = useHistory();

	const submit = async (payload: Inputs) => {
		setProcessing(true);
		try {
			await axios.post(route('v1.auth.register'), payload);
			toastr.success('Registered successfully, please verify your email address.');
			reset();
		} catch (error) {
			handleError(error);
		} finally {
			setProcessing(false);
		}
	};

	useEffect(() => {
		if (user) {
			history.push(getUserMainPage(user));
		}
		// esline-disable-next-line
	}, []);

	return (
		<CenterFlexContainer>
			<Card>
				<Header title='Sign In' description='Create your Payag Ave account.' picture={logo} />
				<Body>
					<form onSubmit={handleSubmit(submit)}>
						<Input {...register('name')} type='text' id='name' placeholder='Full Name' disabled={processing} />
						<Input {...register('phone')} type='text' id='phone' placeholder='Phone No.' disabled={processing} />
						<Input {...register('email')} type='email' id='email' placeholder='Email' disabled={processing} />
						<Input {...register('password')} type='password' id='password' placeholder='Password' disabled={processing} />
						<Input
							{...register('password_confirmation')}
							type='password'
							id='pasword_confirmation'
							placeholder='Confirm Password'
							disabled={processing}
						/>
						<Button type='submit' buttonSize='sm' className='mt-4' disabled={processing}>
							{processing ? <Spinner className='mr-2' /> : null}
							Sign Up
						</Button>
					</form>
					<div className='flex mt-6'>
						Already have an account? <Link to={routes.LOGIN}>Sign In</Link>
					</div>
				</Body>
			</Card>
		</CenterFlexContainer>
	);
};

export default Register;
