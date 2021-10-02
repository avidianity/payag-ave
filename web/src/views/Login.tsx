import React, { FC } from 'react';
import Button from '../components/Button';
import Card from '../components/Card';
import Body from '../components/Card/Body';
import Header from '../components/Card/Header';
import CenterFlexContainer from '../components/CenterFlexContainer';
import Checkbox from '../components/Forms/Checkbox';
import Input from '../components/Forms/Input';
import Link from '../components/Link';
import { routes } from '../routes';
import logo from '../assets/logo-rounded.png';
import { useForm } from 'react-hook-form';
import { useToggle } from '@avidian/hooks';
import { UserInterface } from '../interfaces/user.interface';
import { useHistory } from 'react-router';
import Spinner from '../components/Spinner';
import axios from 'axios';
import { handleError } from '../helpers';
import { LoginResponse } from '../responses/login.response';
import { useGlobalState } from '../hooks';

type Props = {};

type Inputs = Pick<UserInterface, 'email' | 'password'> & {
	remember_me: boolean;
};

const Login: FC<Props> = (props) => {
	const [processing, setProcessing] = useToggle(false);
	const { register, handleSubmit, reset } = useForm<Inputs>();
	const history = useHistory();
	const state = useGlobalState();

	const submit = async (payload: Inputs) => {
		setProcessing(true);
		try {
			const {
				data: { user, token },
			} = await axios.post<LoginResponse>('/v1/auth/login', payload);

			if (payload.remember_me) {
				state.set('token', token);
			}

			reset();
			history.push(routes.DASHBOARD);
		} catch (error: any) {
			handleError(error);
		} finally {
			setProcessing(false);
		}
	};

	return (
		<CenterFlexContainer>
			<Card>
				<Header title='Sign In' description='Login to stay connected.' picture={logo} />
				<Body>
					<form onSubmit={handleSubmit(submit)}>
						<Input {...register('email')} type='email' id='email' placeholder='Email' disabled={processing} />
						<Input {...register('password')} type='password' id='password' placeholder='Password' disabled={processing} />
						<Checkbox {...register('remember_me')} label='Remember Me' />
						<Button type='submit' buttonSize='sm' className='mt-4' disabled={processing}>
							{processing ? <Spinner className='mr-2' /> : null}
							Sign In
						</Button>
					</form>
					<div className='flex mt-6'>
						Don't have an account? <Link to={routes.REGISTER}>Sign Up</Link>
					</div>
				</Body>
			</Card>
		</CenterFlexContainer>
	);
};

export default Login;
