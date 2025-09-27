import { useState } from 'react'
import { useNavigate } from 'react-router-dom'

const SKIP_AUTH = import.meta.env.VITE_SKIP_AUTH === 'true'

export default function Login() {
    const [error, setError] = useState(null)
    const navigate = useNavigate()

    async function handleSubmit(e) {
        e.preventDefault()
        const form = new FormData(e.target)

        // En modo dev skip, no hacemos fetch real
        if (SKIP_AUTH) {
            // opcional: puedes validar campos antes de simular
            localStorage.setItem('mock_auth', '1')
            navigate('/admin/dashboard')
            return
        }

        // modo normal: enviar al backend PHP
        const res = await fetch('/admin/api/login.php', {
            method: 'POST',
            body: new URLSearchParams(form)
        })
        // si el backend redirige, navegamos; si no, mostramos error
        if (res.redirected) {
            window.location.href = res.url
        } else {
            setError('Usuario o contraseña incorrectos')
        }
    }

    // Si SKIP_AUTH activo, mostramos botón extra para entrar rápido
    return (
        <div className="container" style={{ maxWidth: "400px", margin: "60px auto" }}>
            <h1>Panel • Login</h1>
            <form onSubmit={handleSubmit}>
                <label>Usuario</label>
                <input name="user" required />
                <label>Contraseña</label>
                <input type="password" name="pass" required />
                <input type="hidden" name="csrf" value="" />
                <button type="submit" className="btn">Entrar</button>
            </form>

            <div style={{ marginTop: 12 }}>
                {error && <p style={{ color: 'red' }}>{error}</p>}
                {SKIP_AUTH && (
                    <>
                        <hr />
                        <p style={{ fontSize: 13, color: '#c9c9c9' }}>Modo dev: autenticación simulada</p>
                        <button
                            className="btn"
                            onClick={() => {
                                localStorage.setItem('mock_auth', '1')
                                navigate('/admin/dashboard')
                            }}
                        >
                            Simular login (dev)
                        </button>
                        <button
                            style={{ marginLeft: 8 }}
                            className="btn"
                            onClick={() => {
                                localStorage.removeItem('mock_auth')
                                setError(null)
                            }}
                        >
                            Reset mock
                        </button>
                    </>
                )}
            </div>
        </div>
    )
}
