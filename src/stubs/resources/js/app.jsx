import React from 'react';
import ReactDOM from 'react-dom/client';

// Ejemplos de componentes de React
// import Dashboard from './components/pages/Dashboard';
// import Usuarios from './components/pages/Usuarios';

const views = {
//   dashboard: Dashboard,
//   usuarios: Usuarios,
};

const el = document.getElementById('react-root');

if (el) {
  const viewName = el.dataset.view;
  const ViewComponent = views[viewName] ?? (() => <p>Vista no encontrada</p>);

  const root = ReactDOM.createRoot(el);
  root.render(<ViewComponent />);
}
