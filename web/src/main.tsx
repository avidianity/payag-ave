import React from 'react';
import ReactDOM from 'react-dom';
import './boot';
import App from './App';
import './styles/tailwind.css';
import '@fontsource/lato';
import '@fontsource/material-icons';
import 'line-awesome/dist/font-awesome-line-awesome/css/all.css';
import './styles/scrollbar.css';
import './styles/modal-image.css';

ReactDOM.render(
	<React.StrictMode>
		<App />
	</React.StrictMode>,
	document.getElementById('root')
);
