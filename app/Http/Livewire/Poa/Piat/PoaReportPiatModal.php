<?php

namespace App\Http\Livewire\Poa\Piat;

use App\Http\Livewire\Poa\Activity\Exception;
use App\Jobs\Poa\CreateMatrixReportAgreementsCommitments;
use App\Jobs\Poa\CreatePoaActivityPiatReport as PoaCreatePoaActivityPiatReport;
use App\Jobs\Poa\DeletePoaMatrixReportAgreementsCommitments;
use App\Models\Auth\User;
use App\Models\Common\CatalogGeographicClassifier;
use App\Models\Poa\PoaActivityPiat;
use App\Models\Poa\PoaActivityPiatReport;
use App\Models\Poa\PoaMatrixReportBeneficiaries;
use App\Traits\Jobs;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use PhpOffice\PhpSpreadsheet\IOFactory;
use function flash;
use function redirect;
use function user;

class PoaReportPiatModal extends Component
{
    use Jobs, WithFileUploads;

    //For PoaActivityPiat table
    public $activity;
    public $name;
    public $place;
    public $date;
    public $initTime;
    public $endTime;
    public $province;
    public $canton;
    public $parish;
    public $poaActivity;
    public $numberMaleResp = 0;
    public $numberFeMaleResp = 0;
    public $maleBenef = 0;
    public $femaleBenef = 0;
    public $maleVol = 0;
    public $femaleVol = 0;
    public $createdBy;
    public $approvedBy;

    public $description;
    public $accomplished = true;
    public $positiveEvaluation;
    public $evalForInprove;
    public $reportCreatedBy;
    public $reportApprovedBy;
    public $reportDate;
    public $reportInitTime;
    public $reportEndTime;

    public $piatReportAgreComm = [];
    public $agreCommDescription;
    public $agreCommResponsable;
    public $flagShowTabs = false;

    public $planId;


    public $provinces;
    public $cantons = [];
    public $parishes = [];
    public $users;

    public $flag = false;

    public $piat;

    public $piatReport;

    public $annexed;
    public $file = null;

    public $contMen = 0;
    public $contWomen = 0;
    public $contDisability = 0;
    public $under6 = 0;
    public $btw6And12 = 0;
    public $btw13And17 = 0;
    public $btw18And29 = 0;
    public $btw30And39 = 0;
    public $btw40And49 = 0;
    public $btw50And59 = 0;
    public $btw60And69 = 0;
    public $btw70And79 = 0;
    public $greaterThan80 = 0;
    public $piatReportApproved;
    public $showEditor = true;
    public $answer = null;
    public $identifier;
    public $text = '';

    protected $listeners = [
        'loadReportForm' => 'edit',
    ];

    protected $rules = [
        'name' => 'required',
        'date' => 'required',
        'initTime' => 'required',
        'endTime' => 'required',
        'province' => 'required',
        'canton' => 'required',
        'parish' => 'required',
    ];

    public function store()
    {

    }

    public function mount()
    {
        $this->provinces = CatalogGeographicClassifier::where('type', CatalogGeographicClassifier::TYPE_PROVINCE)->get();
        $this->cantons = CatalogGeographicClassifier::where('type', CatalogGeographicClassifier::TYPE_CANTON)->get();
        $this->parishes = CatalogGeographicClassifier::where('type', CatalogGeographicClassifier::TYPE_PARISH)->get();
        $this->users = User::where('enabled', true)->get();
    }

    public function edit($id = null)
    {
        if ($id) {
            $this->piat = PoaActivityPiat::find($id);
            $this->piatReport = PoaActivityPiatReport::with('poaMatrixReportAgreementCommitment')
                ->where('id_poa_activity_piat', $id)->first();

            $this->name = $this->piat->name;
            $this->place = $this->piat->place;
            $this->date = $this->piat->date;
            $this->initTime = $this->piat->initial_time;
            $this->endTime = $this->piat->end_time;
            $this->province = $this->piat->province;
            $this->canton = $this->piat->canton;
            $this->parish = $this->piat->parish;
            $this->numberMaleResp = $this->piat->number_male_respo;
            $this->numberFeMaleResp = $this->piat->number_female_respo;
            $this->maleBenef = $this->piat->males_beneficiaries;
            $this->femaleBenef = $this->piat->females_beneficiaries;
            $this->maleVol = $this->piat->males_volunteers;
            $this->femaleVol = $this->piat->females_volunteers;
//            $this->piatReportApproved = $this->piatReport->approved_by;
            $this->reportDate = $this->piat->date;
            $this->reportInitTime = $this->piat->initial_time;
            $this->reportEndTime = $this->piat->end_time;

            $this->agreCommDescription = null;
            $this->agreCommResponsable = null;

            if ($this->piatReport) {
                $this->beneficiariesReport();
                $this->flagShowTabs = true;
                $this->description = $this->piatReport->description;
                $this->accomplished = $this->piatReport->accomplished;
                $this->positiveEvaluation = $this->piatReport->positive_evaluation;
                $this->evalForInprove = $this->piatReport->evaluation_for_improvement;
                $this->reportCreatedBy = $this->piatReport->created_by;
                $this->reportApprovedBy = $this->piatReport->approved_by;

                $this->reportDate = $this->piatReport->date;
                $this->reportInitTime = $this->piatReport->initial_time;
                $this->reportEndTime = $this->piatReport->end_time;

                $this->piatReportAgreComm = [];
                $piatAgreComm = $this->piatReport->poaMatrixReportAgreementCommitment;

                if ($piatAgreComm) {
                    foreach ($piatAgreComm as $agreComm) {
                        $element = [];
                        $element['id'] = $agreComm->id;
                        $element['id_poa_activity_piat_report'] = $this->piatReport->id;
                        $element['description'] = $agreComm->description;
                        $element['responsable'] = $agreComm->responsable;

                        array_push($this->piatReportAgreComm, $element);
                    }
                }
            }
        }
    }

    public function addAgreementsCommitments()
    {
        $objAgreComm = [];

        $objAgreComm['id'] = time() . rand(1000000, 9000000);
        $objAgreComm['id_poa_activity_piat_report'] = $this->piatReport->id;
        $objAgreComm['description'] = $this->agreCommDescription;
        $objAgreComm['responsable'] = $this->agreCommResponsable;

        array_push($this->piatReportAgreComm, $objAgreComm);

        $this->agreCommDescription = null;
        $this->agreCommResponsable = null;
    }

    public function deleteAgreementsCommitments($idValue)
    {
        foreach ($this->piatReportAgreComm as $item) {
            $aux = (int)$item['id'];
            if ($aux === $idValue && ($key = array_search($item, $this->piatReportAgreComm)) !== false) {
                unset($this->piatReportAgreComm[$key]);
                $response = $this->ajaxDispatch(new DeletePoaMatrixReportAgreementsCommitments($item));
                if ($response['success']) {
                    flash(trans_choice('messages.success.deleted', 0, ['type' => __('poa.piat_matrix_report_divider_agreement_commitment')]))->success()->livewire($this);
                } else {
                    $this->dispatchBrowserEvent('alert', ['title' => $response['message'], 'icon' => 'error']);
                }
                break;
            }
        }
    }

    public function closeModal()
    {
        $this->emit('togglePiatReportModal');

        return redirect()->route('activities.edit', ['activity' => $this->activity->id]);
    }

    public function updatedAccomplished($value)
    {
        $this->accomplished = $value;
    }

    public function submit()
    {

        $this->validate();
        $idReport = null;
        if ($this->piatReport) {
            $idReport = $this->piatReport->id;
        }
        $data = [
            'id' => $idReport,
            'id_poa_activity_piat' => $this->piat->id,
            'accomplished' => $this->accomplished,
            'description' => $this->description,
            'positive_evaluation' => $this->positiveEvaluation,
            'evaluation_for_improvement' => $this->evalForInprove,
            'created_by' => user()->id,
            'approved_by' => -1,
            'date' => $this->reportDate,
            'initial_time' => $this->reportInitTime,
            'end_time' => $this->reportEndTime,
        ];

        $response = $this->ajaxDispatch(new PoaCreatePoaActivityPiatReport($data));

        if ($response['success']) {
            $this->piatReport = $response['data'];
            $this->flagShowTabs = true;
            flash(trans_choice('messages.success.added_or_updated', 0, ['type' => __('general.poa_activity_piat_report')]))->success()->livewire($this);
        } else {
            $this->dispatchBrowserEvent('alert', ['title' => $response['message'], 'icon' => 'error']);
        }
    }

    public function submitAgreementsCommitments()
    {
        $this->validate();
        $response = $this->ajaxDispatch(new CreateMatrixReportAgreementsCommitments($this->piatReportAgreComm));

        if ($response['success']) {
            flash(trans_choice('messages.success.added_or_updated', 0, ['type' => __('general.poa_activity_piat_report_agreements')]))->success()->livewire($this);
        } else {
            $this->dispatchBrowserEvent('alert', ['title' => $response['message'], 'icon' => 'error']);
        }
    }

    public function getCsvFile()
    {

        $this->validate([
            'file' => 'required|mimes:csv,xlsx',
        ]);

        try {
            $name = $this->file->getClientOriginalName();
            $tempPath = $this->file->getRealPath();

            $spreadsheet = IOFactory::load($tempPath);

            $sheet = $spreadsheet->getActiveSheet();
            $row_limit = $sheet->getHighestDataRow();
            $row_range = range(2, $row_limit);

            $data = array();
            $dataPivot = [];
            $pivot = array();

            $cont = 0;

            DB::beginTransaction();

            $beneficiaryArray = array();
            $beneficiaries = $this->piatReport->poaMatrixReportBeneficiaries()->get();
            foreach ($beneficiaries as $beneficiary) {
                $beneficiaryArray[] = [
                    'id' => $beneficiary->id,
                ];
            }

            $this->piatReport->poaMatrixReportBeneficiaries()->delete();
            $this->piatReport->poaMatrixReportBeneficiaries()->detach($beneficiaryArray);

            foreach ($row_range as $row) {
                $data[] = [
                    'names' => $sheet->getCell('A' . $row)->getValue(),
                    'surnames' => $sheet->getCell('B' . $row)->getValue(),
                    'gender' => $sheet->getCell('C' . $row)->getFormattedValue(),
                    'identification' => $sheet->getCell('D' . $row)->getFormattedValue(),
                    'disability' => $sheet->getCell('E' . $row)->getValue(),
                    'age' => $sheet->getCell('F' . $row)->getValue(),
                ];

                $benefCreated = PoaMatrixReportBeneficiaries::create($data[$cont]);

                $pivot[$benefCreated->id] = [
                    'observations' => $sheet->getCell('G' . $row)->getValue(),
                    'belong_to_board' => $sheet->getCell('H' . $row)->getValue(),
                    'participation_initial_time' => $sheet->getCell('I' . $row)->getFormattedValue(),
                    'participation_end_time' => $sheet->getCell('J' . $row)->getFormattedValue(),
                ];

                array_push($dataPivot, $pivot);

                $cont++;
            }

            $this->piatReport->poaMatrixReportBeneficiaries()->sync($dataPivot[count($dataPivot) - 1]);

            DB::commit();

            flash(trans_choice('messages.success.document_added', ['type' => __('general.poa_activity_piat_report_agreements')]))->success()->livewire($this);

            $this->piatReport->refresh();
            $this->beneficiariesReport();

        } catch (Exception $exception) {
            DB::rollback();
            throw new Exception($exception->getMessage());
        }
    }

    public function beneficiariesReport()
    {
        $beneficiaries = $this->piatReport->poaMatrixReportBeneficiaries;

        $this->contWomen = 0;
        $this->contMen = 0;
        $this->contDisability = 0;

        $this->under6 = 0;
        $this->btw6And12 = 0;
        $this->btw13And17 = 0;
        $this->btw18And29 = 0;
        $this->btw30And39 = 0;
        $this->btw40And49 = 0;
        $this->btw50And59 = 0;
        $this->btw60And69 = 0;
        $this->btw70And79 = 0;
        $this->greaterThan80 = 0;

        foreach ($beneficiaries as $beneficiary) {
            if ($beneficiary->gender === 'M') {
                $this->contWomen++;
            } else {
                $this->contMen++;
            }

            if ($beneficiary->disability === 'SI') {
                $this->contDisability++;
            }

            $age = $beneficiary->age;
            switch ($age) {
                case ($age < 6):
                    $this->under6++;
                    break;
                case ($age >= 6 && $age <= 12):
                    $this->btw6And12++;
                    break;
                case ($age >= 13 && $age <= 17):
                    $this->btw13And17++;
                    break;
                case ($age >= 18 && $age <= 29):
                    $this->btw18And29++;
                    break;
                case ($age >= 30 && $age <= 39):
                    $this->btw30And39++;
                    break;
                case ($age >= 40 && $age <= 49):
                    $this->btw40And49++;
                    break;
                case ($age >= 50 && $age <= 59):
                    $this->btw50And59++;
                    break;
                case ($age >= 60 && $age <= 69):
                    $this->btw60And69++;
                    break;
                case ($age >= 70 && $age <= 79):
                    $this->btw70And79++;
                    break;
                case ($age > 80):
                    $this->greaterThan80++;
                    break;
            }
        }
    }

    public function approveReport()
    {
        if (user()->can('approve-piat-report-poa')) {
            $this->piatReport->update(['approved_by' => user()->id]);
            flash(trans_choice('messages.success.approved', 0, ['type' => trans('general.poa_activity_piat_report')]))->success()->livewire($this);
        }
    }
}
