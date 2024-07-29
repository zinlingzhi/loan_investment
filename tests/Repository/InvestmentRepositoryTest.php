namespace App\Tests\Service;

use App\Entity\Investment;
use App\Entity\Tranche;
use App\Repository\InvestmentRepository;
use PHPUnit\Framework\TestCase;

class InvestmentRepositoryTest extends TestCase {
  public function testInvest() {
    $tranche = new Tranche();
    $tranche->setMaxAmount(1000);
    $tranche->setInvestedAmount(0);

    $investment = new Investment();
    $investment->setAmount(1000);

    $investRepository = new InvestmentRepository();
    $this->assertTrue($investRepository->invest($tranhce, $investment));
    $this->assertEquals(1000, $tranche->getInvestedAmount());
  }

  public function testCalculateInterest() {
    $tranche = new Tranche();
    $tranche->setMonthlyInterestRate(3);

    $investment = new Investment();
    $investment->setAmount(1000);
    $investment->setTranche($tranche);
    $investment->setDate(new \DateTime('2023-10-03'));

    $investRepository = new InvestmentRepository();
    $interest = $investRepository->calculateInterest($investment, new \DateTime('2023-10-01'), new \DateTime('2023-10-31'));

    $this->assertEqual(28.06, $interest);
  }
}