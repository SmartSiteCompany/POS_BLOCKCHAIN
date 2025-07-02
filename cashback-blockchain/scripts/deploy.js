const hre = require("hardhat");

async function main() {
  const [deployer] = await hre.ethers.getSigners();

  console.log("Deploying contracts with:", deployer.address);

  const CashbackToken = await hre.ethers.getContractFactory("CashbackToken");
  const token = await CashbackToken.deploy();
  await token.deployed();
  console.log("CashbackToken deployed to:", token.address);

  const CashbackManager = await hre.ethers.getContractFactory("CashbackManager");
  const manager = await CashbackManager.deploy(token.address);
  await manager.deployed();
  console.log("CashbackManager deployed to:", manager.address);

  // Transferir ownership del token al manager
  const tx = await token.transferOwnership(manager.address);
  await tx.wait();
  console.log("Ownership of token transferred to manager.");
}

main().catch((error) => {
  console.error(error);
  process.exitCode = 1;
});
