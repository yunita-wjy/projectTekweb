<?php

?>

<!DOCTYPE html>
<html lang="id">

<style>
    /* ===== MODAL TRANSACTION STYLES ===== */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    z-index: 1000;
    justify-content: center;
    align-items: center;
    padding: 20px;
    animation: fadeIn 0.3s ease;
}

.modal-overlay.active {
    display: flex;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.transaction-modal {
    background-color: white;
    border-radius: 15px;
    width: 100%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Modal Header */
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px;
    border-bottom: 1px solid #eee;
    background-color: var(--bg-secondary, #f8f9fa);
    border-radius: 15px 15px 0 0;
}

.modal-header h3 {
    margin: 0;
    color: var(--text-dark, #0a0a0a);
    font-size: 1.4rem;
    display: flex;
    align-items: center;
}

.modal-header h3 i {
    color: var(--btn-primary, #4ecdc4);
}

.close-modal {
    background: none;
    border: none;
    font-size: 2rem;
    color: #666;
    cursor: pointer;
    line-height: 1;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.2s;
}

.close-modal:hover {
    background-color: rgba(0, 0, 0, 0.1);
    color: #333;
}

/* Modal Body */
.modal-body {
    padding: 25px;
}

/* Movie Info Section */
.movie-info-section {
    display: flex;
    gap: 20px;
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f0f0f0;
}

.movie-poster-placeholder {
    width: 80px;
    height: 100px;
    background: linear-gradient(135deg, var(--btn-primary, #4ecdc4), #2a9d8f);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
    flex-shrink: 0;
}

.movie-details {
    flex: 1;
}

.movie-details h4 {
    margin: 0 0 15px 0;
    color: var(--text-dark, #0a0a0a);
    font-size: 1.2rem;
}

.detail-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 8px;
    color: #555;
    font-size: 0.95rem;
}

.detail-row i {
    color: var(--btn-primary, #4ecdc4);
    width: 20px;
    text-align: center;
}

/* Transaction Details */
.detail-section {
    margin-bottom: 25px;
}

.detail-section h5 {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0 0 15px 0;
    color: var(--text-dark, #0a0a0a);
    font-size: 1.1rem;
}

.detail-section h5 i {
    color: var(--btn-primary, #4ecdc4);
}

/* Seats Container */
.seats-container {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    padding: 10px;
    background-color: var(--bg-secondary, #f8f9fa);
    border-radius: 8px;
    min-height: 50px;
}

.seat-badge-modal {
    background-color: var(--seat-selected, #4ecdc4);
    color: white;
    padding: 8px 15px;
    border-radius: 20px;
    font-weight: 500;
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Summary Table */
.summary-table {
    background-color: var(--bg-secondary, #f8f9fa);
    border-radius: 8px;
    padding: 15px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px dashed #ddd;
    color: #555;
}

.summary-row:last-child {
    border-bottom: none;
}

.summary-row.total {
    font-size: 1.1rem;
    padding-top: 15px;
    margin-top: 5px;
    border-top: 2px solid #ddd;
}

/* Payment Methods */
.payment-methods {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.payment-option {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 15px;
    background-color: var(--bg-secondary, #f8f9fa);
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
    border: 2px solid transparent;
}

.payment-option:hover {
    background-color: #e9ecef;
}

.payment-option.active {
    border-color: var(--btn-primary, #4ecdc4);
    background-color: rgba(78, 205, 196, 0.1);
}

.payment-option i {
    color: var(--btn-primary, #4ecdc4);
    font-size: 1.2rem;
}

/* Modal Footer */
.modal-footer {
    display: flex;
    gap: 15px;
    padding: 20px 25px;
    border-top: 1px solid #eee;
    background-color: var(--bg-secondary, #f8f9fa);
    border-radius: 0 0 15px 15px;
}

.btn-cancel, .btn-payment {
    flex: 1;
    padding: 14px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 1rem;
    border: none;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-cancel {
    background-color: #6c757d;
    color: white;
}

.btn-cancel:hover {
    background-color: #5a6268;
    transform: translateY(-2px);
}

.btn-payment {
    background-color: var(--btn-primary, #4ecdc4);
    color: white;
}

.btn-payment:hover {
    background-color: #3db9b1;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(78, 205, 196, 0.3);
}

/* Responsive */
@media (max-width: 576px) {
    .transaction-modal {
        max-width: 95%;
    }
    
    .modal-header,
    .modal-body,
    .modal-footer {
        padding: 15px;
    }
    
    .movie-info-section {
        flex-direction: column;
        text-align: center;
    }
    
    .movie-poster-placeholder {
        align-self: center;
    }
    
    .modal-footer {
        flex-direction: column;
    }
    
    .btn-cancel, .btn-payment {
        width: 100%;
    }
}
    </style>
    <!-- POPUP TRANSACTION DETAIL -->
<div class="modal-overlay" id="transactionModalOverlay">
    <div class="transaction-modal">
        <!-- Modal Header -->
        <div class="modal-header">
            <h3><i class="fas fa-ticket-alt me-2"></i>Transaction Details</h3>
            <button class="close-modal" id="closeModalBtn">&times;</button>
        </div>
        
        <!-- Modal Body -->
        <div class="modal-body">
            <!-- Movie Info -->
            <div class="movie-info-section">
                <div class="movie-poster-placeholder">
                    <i class="fas fa-film"></i>
                </div>
                <div class="movie-details">
                    <h4 id="modalMovieTitle">Movie Title</h4>
                    <div class="detail-row">
                        <i class="far fa-calendar"></i>
                        <span id="modalShowDate">Date: -</span>
                    </div>
                    <div class="detail-row">
                        <i class="far fa-clock"></i>
                        <span id="modalShowTime">Time: -</span>
                    </div>
                    <div class="detail-row">
                        <i class="fas fa-map-marker-alt"></i>
                        <span id="modalStudio">Studio: -</span>
                    </div>
                </div>
            </div>
            
            <!-- Transaction Details -->
            <div class="transaction-details">
                <div class="detail-section">
                    <h5><i class="fas fa-chair"></i> Selected Seats</h5>
                    <div class="seats-container" id="modalSeatsList">
                        <!-- Seats will be populated by JavaScript -->
                    </div>
                </div>
                
                <div class="detail-section">
                    <h5><i class="fas fa-receipt"></i> Payment Summary</h5>
                    <div class="summary-table">
                        <div class="summary-row">
                            <span>Tickets (<span id="modalTicketCount">0</span>x)</span>
                            <span id="modalTicketPrice">Rp 0,-</span>
                        </div>
                        <div class="summary-row">
                            <span>Service Fee</span>
                            <span>Rp 2.500,-</span>
                        </div>
                        <div class="summary-row total">
                            <span><strong>Total Payment</strong></span>
                            <span id="modalTotalPrice"><strong>Rp 0,-</strong></span>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Method (Optional) -->
                <div class="detail-section">
                    <h5><i class="fas fa-credit-card"></i> Payment Method</h5>
                    <div class="payment-methods">
                        <div class="payment-option active">
                            <i class="fas fa-wallet"></i>
                            <span>E-Wallet (OVO/DANA/GoPay)</span>
                        </div>
                        <div class="payment-option">
                            <i class="fas fa-credit-card"></i>
                            <span>Credit/Debit Card</span>
                        </div>
                        <div class="payment-option">
                            <i class="fas fa-university"></i>
                            <span>Bank Transfer</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="modal-footer">
            <button class="btn-cancel" id="cancelTransactionBtn">
                <i class="fas fa-times me-2"></i>Cancel
            </button>
            <button class="btn-payment" id="confirmPaymentBtn">
                <i class="fas fa-lock me-2"></i>Pay Now
            </button>
        </div>
    </div>
</div>
</html>