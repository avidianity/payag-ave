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
import { UserInterface } from '../interfaces/user.interface';
import { useToggle } from '@avidian/hooks';
import { useForm } from 'react-hook-form';
import { useHistory } from 'react-router';
import Spinner from '../components/Spinner';

type Props = {};

type Inputs = Pick<UserInterface, 'id'>;

const Register: FC<Props> = (props) => {
	const [processing, setProcessing] = useToggle(false);
	const { register, handleSubmit, reset } = useForm<Inputs>();
	const history = useHistory();

	const submit = async (data: Inputs) => {
		history.push(routes.DASHBOARD);
	};

	return (
		<CenterFlexContainer>
			<Card>
				<Header title='Sign In' description='Create your Payag Ave account.' picture={logo} />
				<Body>
					<form onSubmit={handleSubmit(submit)}>
						<Input type='text' id='first_name' name='first_name' placeholder='First Name' disabled={processing} />
						<Input type='text' id='last_name' name='last_name' placeholder='Last Name' disabled={processing} />
						<Input type='text' id='phone_number' name='phone_number' placeholder='Phone No.' disabled={processing} />
						<Input type='email' id='email' name='email' placeholder='Email' disabled={processing} />
						<Input type='password' id='password' name='password' placeholder='Password' disabled={processing} />
						<Input
							type='password'
							id='confirm_password'
							name='confirm_password'
							placeholder='Confirm Password'
							disabled={processing}
						/>
						<Checkbox label='Remember Me' />
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
