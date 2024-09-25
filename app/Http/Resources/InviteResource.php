<?php

namespace App\Http\Resources;

use App\Models\Project;
use App\Models\SurveyProgram;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InviteResource extends JsonResource
{

    private function getType()
    {
        if ($this->survey_program_id) {
            return 'surveyProgram';
        } else if ($this->project_id) {
            return 'project';
        } else {
            return 'workspace';
        }
    }

    private function getName()
    {
        if ($this->survey_program_id) {
            return SurveyProgram::find($this->survey_program_id)->name;
        } else if ($this->project_id) {
            return Project::find($this->project_id)->name;
        } else {
            return Workspace::find($this->workspace_id)->name;
        }
    }


    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->user->email,
            'active' => $this->user->active,
            'note' => $this->user->note,
            'photo' => $this->user->photo,
            'is_verified' => $this->user->is_verified,
            'accepted' => $this->accepted,
            'occupation' => $this->user->occupation,
            'userable' => [
                'type_name' => $this->user->userable_type,
                'certificates' => $this->user->certificates,
                'user' => new UserPersonResource($this->user->userPerson),
            ],
            'name' => self::getName(),
            'permissions' => $this->permissions,
            'type' => self::getType()
        ];
    }
}
