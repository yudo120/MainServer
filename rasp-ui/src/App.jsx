import { BrowserRouter, Routes, Route } from "react-router-dom";
import Navbar from "./components/Navbar";
import Home from "./pages/Home";
import "./styles/global.css";

export default function App() {
    const loggedIn = false; // luego conectarás con tu backend/session

    return (
        <BrowserRouter>
            <Navbar loggedIn={loggedIn} />
            <Routes>
                <Route path="/" element={<Home />} />
                <Route path="/docs" element={<div>Documentación</div>} />
                <Route path="/servicios" element={<div>Lista de servicios</div>} />
                <Route path="/tools" element={<div>Herramientas</div>} />
            </Routes>
        </BrowserRouter>
    );
}
