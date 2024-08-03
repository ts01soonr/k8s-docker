using UnitTestCaseDemo.Model;

namespace UnitTestCaseDemo.Services
{
    public class ClientService : IClientService
    {   
        //using static holding list in memory
        private static List<Client> _clients = new List<Client>();
        private static int _id = 0;
        public ClientService()
        {
            Init();
        }
        private void Init()
        {
            if (_clients.Count == 0)
            {
                _clients = new List<Client>() {
                    new Client() {
                        Id = _id++,
                        Name = "George Client",
                        PhoneNo = "11111111111",
                        EmailId = "george@email.com"
                    },
                    new Client() {
                        Id = _id++,
                        Name = "Amanda Client",
                        PhoneNo = "22222222222",
                        EmailId = "amanda@email.com"
                    },
                    new Client() {
                        Id = _id++,
                        Name = "Peter Client",
                        PhoneNo = "33333333",
                        EmailId = "amanda@email.com"
                    }
                };

            }

        }
        public Client Add(Client client)
        { 
            if (client.Id ==0 ) client.Id = _id;
            _id++;
            _clients.Add(client);
            Console.WriteLine(_clients.Count);
            return client;
        }

        public bool Delete(int id)
        {
            if (id == -1) {
                _clients.Clear();
                return _clients.Count == 0;
            }
            var existing = _clients.Find(x => x.Id == id);
            if (existing != null)
            {
                _clients.Remove(existing);
                return true;
            }
            else
                return false;
        }

        public List<Client> GetAll()
        {
            return _clients;
        }

        public Client GetById(int id)
        {
            return _clients.Where(x => x.Id == id).FirstOrDefault();
        }
    }
}
