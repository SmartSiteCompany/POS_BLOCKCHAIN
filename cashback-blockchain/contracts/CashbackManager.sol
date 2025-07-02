// SPDX-License-Identifier: MIT
pragma solidity ^0.8.20;

import "./CashbackToken.sol";
import "@openzeppelin/contracts/access/Ownable.sol";

contract CashbackManager is Ownable {
    CashbackToken public token;

    constructor(address tokenAddress) {
        token = CashbackToken(tokenAddress);
    }

    function grantCashback(address customer, uint256 purchaseAmount) external onlyOwner {
        // 1% de cashback
        uint256 cashback = (purchaseAmount * 1) / 100;
        token.mint(customer, cashback);
    }
}
