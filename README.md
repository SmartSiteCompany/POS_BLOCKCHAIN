# 🪙 Cashback Blockchain Module – SmartPOS-X

Este módulo implementa un sistema de recompensas (cashback) basado en tecnología blockchain, permitiendo a los usuarios recibir tokens como incentivo por sus compras en el sistema POS. Brinda transparencia, descentralización y fidelización efectiva.

---

## 🎯 Objetivo

Desarrollar un sistema de recompensas sobre Ethereum testnet mediante smart contracts en Solidity, que interactúe con el backend POS y permita visualizar saldo, historial y reglas de recompensa desde el frontend React.

---

## 🧩 Componentes del Módulo

| Componente           | Descripción |
|----------------------|-------------|
| `CashbackToken.sol`  | Token ERC-20 que representa las recompensas |
| `CashbackManager.sol`| Lógica del contrato: reglas, emisión y validación |
| `deploy.js`          | Script de despliegue (usando Hardhat o Truffle) |
| `web3.service.js`    | Servicio React para conexión vía Web3.js o Ethers.js |
| `CashbackUI.jsx`     | Visualización en frontend: saldo, historial, reglas |
| `wallet-connect.js`  | Integración con MetaMask / WalletConnect |

---

## 🔄 Flujo del Sistema

1. Cliente realiza una compra en el POS.
2. El backend envía los datos de la compra al contrato inteligente.
3. Se calcula y emite el cashback correspondiente.
4. El usuario recibe tokens en su wallet.
5. El usuario puede visualizar su saldo y recompensas en la interfaz.

---

## 📦 Stack Tecnológico

| Área           | Tecnología                    |
|----------------|-------------------------------|
| Smart Contracts | Solidity + OpenZeppelin ERC-20 |
| Testnet         | Ganache / Sepolia             |
| Herramientas    | Hardhat o Truffle             |
| Integración     | Web3.js o Ethers.js           |
| Frontend        | React.js + MetaMask           |
| Base de Datos   | MySQL (para logs y eventos)   |

---

## 🧪 Ejemplo de Regla de Cashback

```solidity
if (amount >= 1000 ether) {
    reward = amount * 5 / 100;
} else {
    reward = amount * 3 / 100;
}
