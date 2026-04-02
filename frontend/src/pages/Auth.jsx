import { useState, useContext } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { AuthContext } from '../App';
import api from '../api';

export function Login() {
  const [form, setForm] = useState({ email: '', password: '', role: 'customer' });
  const [error, setError] = useState('');
  const { setUser } = useContext(AuthContext);
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const res = await api.post('/login.php', form);
      setUser(res.data.user);
      navigate(res.data.user.role === 'agency' ? '/add-car' : '/');
    } catch (err) {
      setError(err.response?.data?.error || 'Login failed');
    }
  };

  return (
    <div className="row justify-content-center"><div className="col-md-5"><div className="card p-4">
      <h3 className="mb-4 text-center">Login</h3>
      {error && <div className="alert alert-danger">{error}</div>}
      <form onSubmit={handleSubmit}>
        <div className="mb-3">
          <label>Login As</label>
          <select className="form-select" value={form.role} onChange={e => setForm({...form, role: e.target.value})}>
            <option value="customer">Customer</option>
            <option value="agency">Agency</option>
          </select>
        </div>
        <div className="mb-3"><label>Email</label><input type="email" required className="form-control" value={form.email} onChange={e => setForm({...form, email: e.target.value})} /></div>
        <div className="mb-3"><label>Password</label><input type="password" required className="form-control" value={form.password} onChange={e => setForm({...form, password: e.target.value})} /></div>
        <button type="submit" className="btn btn-primary w-100">Sign In</button>
      </form>
    </div></div></div>
  );
}

export function RegisterCustomer() {
  const [form, setForm] = useState({ name: '', email: '', phone: '', password: '' });
  const [error, setError] = useState('');
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      await api.post('/register_customer.php', form);
      alert('Registered successfully!');
      navigate('/login');
    } catch (err) {
      setError(err.response?.data?.error || 'Registration failed');
    }
  };

  return (
    <div className="row justify-content-center"><div className="col-md-5"><div className="card p-4">
      <h3 className="mb-4 text-center">Customer Registration</h3>
      {error && <div className="alert alert-danger">{error}</div>}
      <form onSubmit={handleSubmit}>
        <div className="mb-3"><label>Name</label><input type="text" required className="form-control" onChange={e => setForm({...form, name: e.target.value})} /></div>
        <div className="mb-3"><label>Email</label><input type="email" required className="form-control" onChange={e => setForm({...form, email: e.target.value})} /></div>
        <div className="mb-3"><label>Phone</label><input type="text" required className="form-control" onChange={e => setForm({...form, phone: e.target.value})} /></div>
        <div className="mb-3"><label>Password</label><input type="password" minLength="6" required className="form-control" onChange={e => setForm({...form, password: e.target.value})} /></div>
        <button type="submit" className="btn btn-primary w-100">Register</button>
      </form>
    </div></div></div>
  );
}

export function RegisterAgency() {
  const [form, setForm] = useState({ agency_name: '', email: '', address: '', password: '' });
  const [error, setError] = useState('');
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      await api.post('/register_agency.php', form);
      alert('Registered successfully!');
      navigate('/login');
    } catch (err) {
      setError(err.response?.data?.error || 'Registration failed');
    }
  };

  return (
    <div className="row justify-content-center"><div className="col-md-5"><div className="card p-4">
      <h3 className="mb-4 text-center">Agency Registration</h3>
      {error && <div className="alert alert-danger">{error}</div>}
      <form onSubmit={handleSubmit}>
        <div className="mb-3"><label>Agency Name</label><input type="text" required className="form-control" onChange={e => setForm({...form, agency_name: e.target.value})} /></div>
        <div className="mb-3"><label>Email</label><input type="email" required className="form-control" onChange={e => setForm({...form, email: e.target.value})} /></div>
        <div className="mb-3"><label>Address</label><input type="text" required className="form-control" onChange={e => setForm({...form, address: e.target.value})} /></div>
        <div className="mb-3"><label>Password</label><input type="password" minLength="6" required className="form-control" onChange={e => setForm({...form, password: e.target.value})} /></div>
        <button type="submit" className="btn btn-primary w-100">Register</button>
      </form>
    </div></div></div>
  );
}
