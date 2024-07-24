<?php

namespace App\Http\Requests\Collector;

use App\Models\CollectingMilkFromFamily;
use App\Models\Family;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCollectingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'id' => [
                'required',
                'integer',
                'exists:collecting_milk_from_families,id',
                function ($attribute, $value, $fail) {
                    $collectingMilkFromFamily = CollectingMilkFromFamily::findOrFail($value);
                    if ($collectingMilkFromFamily->user_id !== auth('sanctum')->user()->id) {
                        $fail('لم تقم انت باضافة هذه العملية');
                    }
                },
            ],
            'date_and_time' => [
                'required',
                'date_format:Y-m-d H:i:s',
                function ($attribute, $value, $fail) {
                    $requestDateTime = \Carbon\Carbon::parse($value);
                    $now = \Carbon\Carbon::now();
                    $twoDaysAgo = \Carbon\Carbon::now()->subDays(2);
    
                    if ($requestDateTime->greaterThan($now)) {
                        $fail('يجب أن لا يكون تاريخ ووقت الجمع في المستقبل (بعد الوقت الحالي).');
                    }
    
                    if ($requestDateTime->lessThan($twoDaysAgo)) {
                        $fail('يجب أن لا يكون تاريخ ووقت الجمع قبل يومين.');
                    }
                },
            ],
            'quantity' => [
                'required',
                'numeric',
                'min:1',
            ],
            'family_id' => [
                'required',
                'exists:families,id',
                function ($attribute, $value, $fail) {
                    $family =Family::findOrFail($value)->associations_branche_id;
                    if ( $family !== auth('sanctum')->user()->id) {
                        $fail('لم تقم أنت بإضافة هذه الأسرة');
                    }
                },
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages()
    {
        return [
            'id.required' => 'معرف عملية التجميع مطلوب',
            'id.exists' => 'عملية التجميع المحددة غير موجودة',
            'date_and_time.required' => 'تاريخ ووقت الجمع مطلوب',
            'date_and_time.date_format' => 'يجب أن يكون تاريخ ووقت الجمع صالحًا',
            'quantity.required' => 'الكمية مطلوبة',
            'quantity.numeric' => 'الكمية يجب أن تكون رقمية',
            'quantity.min' => 'الكمية يجب أن تكون على الأقل 1',
            'family_id.required' => 'معرف الاسرة مطلوب',
            'family_id.exists' => 'الاسرة المحددة غير موجودة',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errorMessages = $validator->errors()->all();
        $mergedMessage = implode(' و ', $errorMessages);

        throw new \Illuminate\Http\Exceptions\HttpResponseException(response()->json([
            'success' => false,
            'message' => $mergedMessage,
        ], 422));
    }
}
