<?php

class FeedbackController extends _MainController {

	const HALAMAN_ADMIN_FEEDBACK = 'admin/feedback.html';
	
	function checkNotifikasi($id, $tipe) {
		$pengguna = Auth::getPengguna();
		$notifikasi1 = Notifikasi::where('review_id', '=', $id)->where('pengguna_npm', '=', $pengguna->npm)->where('tipe', '=', $tipe)->get();
		if($notifikasi1->count() > 0) {
			$notifikasi = $notifikasi1->last();
			if($notifikasi->updated_at >= $pengguna->updated_at) {
				$total = $notifikasi->total;
				$notifikasi->total = $total+1;
				$notifikasi->save();
			}
			else {
				$notifikasi1 = new Notifikasi;
				$notifikasi1->tipe = $tipe;
				$notifikasi1->pengguna_npm = $pengguna->npm;
				$notifikasi1->total = 1;
				$notifikasi1->review_id = $id;
				$notifikasi1->dosen_id = 0;
				$notifikasi1->read = 0;
				$notifikasi1->save();
			}
		}
		else {
				$notifikasi1 = new Notifikasi;
				$notifikasi1->tipe = $tipe;
				$notifikasi1->pengguna_npm = $pengguna->npm;
				$notifikasi1->total = 1;
				$notifikasi1->review_id = $id;
				$notifikasi1->dosen_id = 0;
				$notifikasi1->read = 0;
				$notifikasi1->save();
		}
	}
	
	function checkReward($tipe) {
		$pengguna = Auth::getPengguna();
		$model = Feedback::where('pengguna_npm', '=', $pengguna->npm);
		$total = $model->count();
		foreach (Achievment::where('tipe', '=', $tipe)->get() as $achiev) {
			if($achiev->target <= $total) {
				$reward = $achiev->penggunas()->get();
				if($reward->count() != 0) {
					if($reward->first()->pivot->where('pengguna_nomor', '=', $pengguna->npm)->count() < 1) {
						$pengguna1 = Pengguna::where('npm', '=', $pengguna->npm)->first();
						$achiev->penggunas()->attach($pengguna1->npm);
					}
				}
				else {
					$pengguna1 = Pengguna::where('npm', '=', $pengguna->npm)->first();
					$achiev->penggunas()->attach($pengguna1->npm);
				}
				$this->checkNotifikasi(0, 'achievment');
			}
		}
	}
	
	function tambahFeedback() {
		$feedback = $this->app->request->post();
		$rating = $feedback['rating'];
		$isi = $feedback['feedback'];

		$pengguna = Auth::getPengguna();
		$npm = $pengguna->npm;
		$feedback1 = new Feedback;
		$feedback1->rating = $rating;
		$feedback1->isi = $isi;
		$feedback1->pengguna_npm = $npm;
		$feedback1->save();
		
		$activity1 = new ActivityLog;
		$activity1->activity = "memberi Feedback";
		$activity1->pengguna_npm = $pengguna->npm;
		$activity1->dosen_id = 0;
		$activity1->save();
		
		$this->app->flash('notif', 'Terima kasih telah memberi feedback! Feedback anda akan sangat berguna untuk perkembangan rekomendosen kedepannya!');
		$this->checkReward('feedback');
		$this->app->response->redirect($this->app->urlFor('home'), 400);
	}
	
	function tampilAdministrasiFeedback() {
		$feedback = Feedback::all();
		$data = array();
		$data['daftarFeedback'] = $feedback;
		$this->render(self::HALAMAN_ADMIN_FEEDBACK,$data);
	}
	
	function deleteFeedback($id) {
		$feedback = Feedback::where('id', '=', $id)->delete();
		$this->app->flash('notif', 'Berhasil menghapus feedback id #' . $id);
		$this->app->response->redirect($this->app->urlFor('rdfeedback'), 400);
	}
}