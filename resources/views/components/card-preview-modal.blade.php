<!-- Card Preview Modal -->
<div class="modal fade" id="cardPreviewModal" tabindex="-1" aria-labelledby="cardPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cardPreviewModalLabel">
                    <i class="fas fa-id-card me-2"></i>
                    معاينة بطاقة المشترك
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
            </div>
            <div class="modal-body p-0">
                <!-- سيتم تحميل محتوى البطاقة هنا -->
            </div>
        </div>
    </div>
</div>

<style>
.card-preview-container {
    padding: 20px;
}

.card-preview-wrapper {
    max-width: 600px;
    margin: 0 auto;
    perspective: 1000px;
}

.card-preview-controls {
    text-align: center;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #dee2e6;
}

.card-preview-controls .btn {
    margin: 0 5px;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-flip {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
}

.btn-flip:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    color: white;
}

.btn-print {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border: none;
}

.btn-print:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
    color: white;
}

.btn-download {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    color: white;
    border: none;
    text-decoration: none;
}

.btn-download:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(23, 162, 184, 0.4);
    color: white;
    text-decoration: none;
}

/* Card Flip Animation */
.card-flip {
    position: relative;
    transform-style: preserve-3d;
    transition: transform 0.6s;
}

.card-flip.flipped {
    transform: rotateY(180deg);
}

.card-front,
.card-back {
    position: relative;
    backface-visibility: hidden;
}

.card-back {
    transform: rotateY(180deg);
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .modal-xl {
        max-width: 95%;
    }
    
    .card-preview-container {
        padding: 10px;
    }
    
    .card-preview-controls .btn {
        display: block;
        width: 100%;
        margin: 5px 0;
    }
}

/* Print styles for modal */
@media print {
    .modal-header,
    .card-preview-controls,
    .btn-close {
        display: none !important;
    }
    
    .modal-dialog {
        max-width: none !important;
        margin: 0 !important;
    }
    
    .modal-content {
        border: none !important;
        box-shadow: none !important;
    }
    
    .card-preview-container {
        padding: 0 !important;
    }
}
</style>

<script>
let isCardFlipped = false;

function flipCard() {
    const cardWrapper = document.querySelector('.card-preview-wrapper');
    const flipBtn = document.querySelector('.btn-flip');
    
    if (!cardWrapper) return;
    
    isCardFlipped = !isCardFlipped;
    
    if (isCardFlipped) {
        cardWrapper.classList.add('card-flip', 'flipped');
        flipBtn.innerHTML = '<i class="fas fa-eye me-2"></i>عرض الوجه الأمامي';
    } else {
        cardWrapper.classList.remove('flipped');
        flipBtn.innerHTML = '<i class="fas fa-eye me-2"></i>عرض الوجه الخلفي';
    }
}

function printCard() {
    // إخفاء العناصر غير المرغوب فيها مؤقتاً
    const elementsToHide = document.querySelectorAll('.modal-header, .card-preview-controls, .btn-close');
    elementsToHide.forEach(el => el.style.display = 'none');
    
    // طباعة النافذة
    window.print();
    
    // إعادة إظهار العناصر
    setTimeout(() => {
        elementsToHide.forEach(el => el.style.display = '');
    }, 100);
}

// إعادة تعيين حالة البطاقة عند إغلاق النافذة المنبثقة
document.getElementById('cardPreviewModal').addEventListener('hidden.bs.modal', function () {
    isCardFlipped = false;
    const cardWrapper = document.querySelector('.card-preview-wrapper');
    const flipBtn = document.querySelector('.btn-flip');
    
    if (cardWrapper) {
        cardWrapper.classList.remove('card-flip', 'flipped');
    }
    
    if (flipBtn) {
        flipBtn.innerHTML = '<i class="fas fa-eye me-2"></i>عرض الوجه الخلفي';
    }
});
</script>
