import { useState, useEffect, useContext } from 'react';
import { AuthContext } from '../App';
import { Link, useNavigate } from 'react-router-dom';
import api from '../api';

export default function Cars() {
  const { user } = useContext(AuthContext);
  const [cars, setCars] = useState([]);
  const [bookedCars, setBookedCars] = useState([]);
  const [error, setError] = useState('');
  const navigate = useNavigate();

  const fetchCars = async () => {
    try {
      const res = await api.get('/cars.php');
      setCars(res.data.cars);
      if (res.data.booked_cars) {
        setBookedCars(res.data.booked_cars);
      }
    } catch (err) {}
  };

  useEffect(() => { fetchCars(); }, []);

  const handleRent = async (e, carId) => {
    e.preventDefault();
    const start_date = e.target.start_date.value;
    const num_days = e.target.num_days.value;

    try {
      await api.post('/cars.php', { car_id: carId, start_date, num_days });
      alert("Car booked successfully!");
      setBookedCars(prev => [...prev, carId]);
    } catch (err) {
      setError(err.response?.data?.error || "Booking failed");
    }
  };

  return (
    <div>
      <h3 className="mb-4">Available Cars to Rent</h3>
      {error && <div className="alert alert-danger">{error}</div>}
      <div className="row">
        {cars.map(car => (
          <div className="col-md-4 mb-4" key={car.id}>
            <div className="card h-100">
              <div className="card-body">
                <h5 className="card-title">{car.model}</h5>
                <p className="card-text text-muted mb-3">Vehicle No: {car.vehicle_number}</p>
                <p><strong>Capacity:</strong> {car.seating_capacity} persons</p>
                <p><strong>Rent:</strong> ${parseFloat(car.rent_per_day).toFixed(2)}/day</p>

                {!user && (
                    <button onClick={() => navigate('/login')} className="btn btn-primary w-100 mt-2">Login to Rent Car</button>
                )}

                {user?.role === 'customer' && (
                  bookedCars.includes(car.id) ? (
                    <div className="alert alert-success py-2 mt-3 text-center mb-0" style={{fontSize: '0.9rem'}}>Already Booked</div>
                  ) : (
                    <form onSubmit={(e) => handleRent(e, car.id)} className="mt-3">
                      <div className="row mb-2">
                         <div className="col"><input type="date" required className="form-control form-control-sm" name="start_date" /></div>
                         <div className="col">
                           <select name="num_days" required className="form-select form-select-sm">
                              <option value="">Days</option>
                              {[1,2,3,4,5,6,7].map(i => <option key={i} value={i}>{i}</option>)}
                           </select>
                         </div>
                      </div>
                      <button type="submit" className="btn btn-primary w-100">Rent Car</button>
                    </form>
                  )
                )}
                
                {user?.role === 'agency' && (
                  <div className="alert alert-secondary py-2 mt-3 text-center mb-0" style={{fontSize: '0.9rem'}}>Agencies cannot book cars.</div>
                )}
                
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
