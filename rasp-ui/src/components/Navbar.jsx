import { Link, NavLink } from "react-router-dom";
import "../styles/navbar.css";

export default function Navbar({ loggedIn }) {
    return (
        <header className="navbar">
            <div className="nav-left">
                {/* Logo / nombre */}
                <Link to="/" className="nav-logo">Raspi</Link>

                {/* Enlaces normales */}
                <nav className="nav-links">
                    <NavLink
                        to={loggedIn ? "/servicios" : "#"}
                        className={({ isActive }) =>
                            `nav-item ${isActive ? "active" : ""} ${!loggedIn ? "disabled" : ""}`
                        }
                    >
                        Servicios
                    </NavLink>

                    <NavLink
                        to={loggedIn ? "/tools" : "#"}
                        className={({ isActive }) =>
                            `nav-item ${isActive ? "active" : ""} ${!loggedIn ? "disabled" : ""}`
                        }
                    >
                        Tools
                    </NavLink>

                    <NavLink
                        to="/docs"
                        className={({ isActive }) => `nav-item ${isActive ? "active" : ""}`}
                    >
                        Docs
                    </NavLink>
                </nav>
            </div>

            <div className="nav-right">
                <button className="nav-btn">Entrar</button>
            </div>
        </header>
    );
}