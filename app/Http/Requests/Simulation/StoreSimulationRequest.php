<?php

namespace App\Http\Requests\Simulation;

use App\Models\MongoDB\Simulation\Simulation;
use App\Models\MongoDB\User\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSimulationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = auth()->user();
        $robotId = $this->input('robot_id');
        $environmentId = $this->input('environment_id');

        // authorize only if user has related entities associated
        return $user->ownsRobot($robotId) && $user->ownsEnvironment($environmentId);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // TODO instead of hardcoding _id, define primary key from model
            'robot_id' => 'required|exists:robots,_id',
            'environment_id' => 'required|exists:environments,_id',
            'name' => 'required',
            'description' => 'required',
            'thumbnail' => 'required|url',
            'status' => Rule::in(Simulation::availableStatuses())
        ];
    }
}
