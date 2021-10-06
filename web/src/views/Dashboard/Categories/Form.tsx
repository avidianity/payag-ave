import { useMode } from '@avidian/hooks';
import React, { FC, useEffect } from 'react';
import { useHistory, useRouteMatch } from 'react-router';
import Button from '../../../components/Buttons/Button';
import View from '../../../components/Dashboard/View';

type Props = {};

const Form: FC<Props> = (props) => {
	const [mode, setMode] = useMode();
	const history = useHistory();
	const match = useRouteMatch<{ id: string }>();

	useEffect(() => {
		if (match.path.includes('edit')) {
			setMode('Edit');
		}
		// eslint-disable-next-line
	}, []);

	return (
		<View>
			<div className='flex items-center mt-8 mb-16'>
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
			</div>
			<div className='border-2 rounded-lg border-gray-200 shadow-lg px-4 py-2'></div>
		</View>
	);
};

export default Form;
