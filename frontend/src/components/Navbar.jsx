import { useContext } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { AuthContext } from '../App';
import api from '../api';

export default function Navbar() {
  const { user, setUser } = useContext(AuthContext);
  const navigate = useNavigate();

  const handleLogout = async () => {
    try {
        await api.post('/logout.php');
    } catch(e){}
    setUser(null);
    navigate('/login');
  };

  return (
    <nav className="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
      <div className="container">
        <Link className="navbar-brand" to="/">CarRental</Link>
        <div className="collapse navbar-collapse show">
          <ul className="navbar-nav me-auto">
            <li className="nav-item">
              <Link className="nav-link" to="/">Available Cars</Link>
            </li>
            {user?.role === 'agency' && (
              <>
                <li className="nav-item">
                  <Link className="nav-link" to="/add-car">Add New Cars</Link>
                </li>
                <li className="nav-item">
                  <Link className="nav-link" to="/bookings">View Booked Cars</Link>
                </li>
              </>
            )}
          </ul>
          <ul className="navbar-nav ms-auto">
            {user ? (
              <>
                <li className="nav-item p-2 text-light">Welcome, {user.name} ({user.role})</li>
                <li className="nav-item">
                  <button onClick={handleLogout} className="btn nav-link">Logout</button>
                </li>
              </>
            ) : (
              <>
                <li className="nav-item"><Link className="nav-link" to="/login">Login</Link></li>
                <li className="nav-item"><Link className="nav-link" to="/register/customer">Customer Registration</Link></li>
                <li className="nav-item"><Link className="nav-link" to="/register/agency">Agency Registration</Link></li>
              </>
            )}
          </ul>
        </div>
      </div>
    </nav>
  );
}
