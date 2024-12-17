<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://js.stripe.com/v3/"></script>
    <title>الدفع الإلكتروني</title>
    <style>
        section.start-transaction {
            text-align: center;
        }

        section.start-transaction h2 {
            font-size: 32px;
        }

        section.start-transaction .body {
            background: #fff;
            padding: 64px 32px;
            max-width: 600px;
            margin: 0 auto;
            border-radius: 8px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
        }

        section.start-transaction .body .form form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        section.start-transaction .body .form .item {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        section.start-transaction .body .form form .btn-container {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 16px;
            justify-content: center;
        }

        #card-errors {
            color: red;
            margin-top: 10px;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        @media screen and (max-width: 480px) {
            section.start-transaction .body {
                padding: 32px;
            }

            section.start-transaction h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <section class="start-transaction">
        <h2>الدفع الإلكتروني</h2>
        <div class="body">
            @if (session('success'))
                <p style="color: green;">{{ session('success') }}</p>
            @endif

            @if (session('error'))
                <p style="color: red;">{{ session('error') }}</p>
            @endif

            <div class="form">
                <form id="payment-form" action="{{ route('payment.process') }}" method="POST">
                    @csrf
                    <div class="item">
                        <label for="amount">المبلغ (بالدولار):</label>
                        <input type="number" id="amount" name="amount" min="1" required>
                    </div>

                    <div class="item">
                        <label>بيانات البطاقة:</label>
                        <div id="card-element" style="width: 100%;"></div>
                    </div>

                    <div id="card-errors" role="alert"></div>

                    <div class="btn-container">
                        <button type="submit">إتمام الدفع</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        const stripe = Stripe('pk_test_51ONXM0DCnvSZulvvRJCUdzqajOBsSoeP1o25GSQctKDvEYf7dgTPJn6XlIGu4aLqjU8mKByPfK4UcCL673wCDwpX00bVUfXybD'); // استبدل بـ API key العلني الخاص بك
        const elements = stripe.elements();
        const cardElement = elements.create('card');

        cardElement.mount('#card-element');

        const form = document.getElementById('payment-form');
        const cardErrors = document.getElementById('card-errors');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const amount = document.getElementById('amount').value;

            if (!amount) {
                cardErrors.textContent = 'يرجى إدخال مبلغ الدفع.';
                return;
            }

            const { token, error } = await stripe.createToken(cardElement);

            if (error) {
                cardErrors.textContent = error.message;
            } else {
                fetch('{{ route('payment.process') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ amount: amount, token: token.id }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم الدفع بنجاح!');
                    } else {
                        alert('فشلت عملية الدفع: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('حدث خطأ أثناء عملية الدفع.');
                });
            }
        });
    </script>
</body>
</html>
