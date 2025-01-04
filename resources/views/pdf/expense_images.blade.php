<!DOCTYPE html>
<html>
<head>
    <title>Reimbursable Expenses</title>
    <style>
        /* General Body Styling */
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7f6;
            color: #333;
            line-height: 1.6;
        }

        /* Main Container */
        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        /* Title Styling */
        h1 {
            text-align: center;
            font-size: 2.5em;
            color: #34495e;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 3px;
        }

        /* Expense Item Styling */
        .expense {
            margin-bottom: 40px;
            border-bottom: 1px solid #e3e3e3;
            padding-bottom: 20px;
        }

        /* Image Styling */
        .image-container {
            text-align: center;
            margin-bottom: 15px;
        }

        .image {
            width: 100%;
            max-width: 600px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s;
        }

        .image:hover {
            transform: scale(1.05);
        }

        /* No Image Styling */
        .not-available {
            font-size: 1.1em;
            font-weight: bold;
            color: #e74c3c;
            text-align: center;
        }

        /* Footer Styling */
        .footer {
            text-align: center;
            font-size: 0.9em;
            color: #7f8c8d;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Reimbursable Expenses</h1>
        
        <!-- Loop through expenses -->
        @foreach($expenses as $item)
            <div class="expense">
                @if ($item['image'] !== '')
                    <div class="image-container">
                        <img class="image" src="{{ asset($item['image']) }}" alt="Expense Image">
                    </div>
                @else
                    <p class="not-available">No Record Found!</p>
                @endif
            </div>
        @endforeach

        <!-- Footer -->
        <div class="footer">
            <p>&copy; {{ date('Y') }} Assets. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
